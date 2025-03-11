<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $wallet = Wallet::where('user_id', Auth::id())->first();

        if (!$wallet) {
            
            $wallet = Wallet::create([
                'user_id' => Auth::id(),
                'available_balance' => 0, 
                
            ]);
        }



        $transaction = Transaction::with('lottery', 'lotteryDashboard')->get();
        $withdrawal = Withdrawal::all();

        $deposit = Deposit::all();

        $bank = Bank::all();

        // dd($wallet);

        return Inertia::render('User/Wallet', [
            'status' => session('status'),
            'wallet' => $wallet,
            'transactions' => $transaction,
            'withdrawal' => $withdrawal,
            'deposit' => $deposit,
            'bank' => $bank
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        //
    }


    public function request(Request $request)
    {
        Log::info("Request Data: ", $request->all());

        $validator = Validator::make($request->all(), [
            'bank' => 'required|string',
            'account_number' => 'required|string',
            'amount' => 'required|numeric',
            'reference' => 'required|string',
            'attachment' => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048"
        ]);



        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('attachment')) {
            $image = $request->file('attachment'); // Get the uploaded file
            $imageName = time() . '_' . $image->getClientOriginalName(); // Generate a unique file name
            $destinationPath = public_path('images/request'); // Path where the file will be saved

            // Create the directory if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move the file to the destination path
            $image->move($destinationPath, $imageName);

            // Store the relative image path for saving in the database
            $imagePath = 'assets/images/request/' . $imageName;
        }

        // Save deposit request
        Deposit::create([
            'wallet_id' => Auth::user()->wallet?->id,
            'amount' => $request->amount,
            'description' => "Deposit request from " . $request->bank,
            'reference' => $request->reference,
            'deposit_date' => now(),
            'image' => $imagePath ?? null,
            'status' => 0,
        ]);

        return response()->json(['message' => 'Deposit request submitted successfully'], 200);
    }

    public function withdraw(Request $request)
    {
        // Log::info("Request Data: ", $request->all());
        $validator = Validator::make($request->all(), [
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:1',
        ]);




        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $wallet = Wallet::where('id', $request->wallet_id)
            ->where('user_id', Auth::id()) // Ensure wallet belongs to user
            ->first();

        if (!$wallet) {
            return response()->json(['error' => 'Wallet not found or does not belong to you'], 404);
        }

        // Check if the wallet has enough balance
        if ($wallet->available_balance < $request->amount) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        // Subtract the amount from available_balance
        $wallet->available_balance -= $request->amount;
        $wallet->save();

        // $wallet = Wallet::find($request->wallet_id);
        Withdrawal::create([
            'wallet_id' => Auth::user()->wallet?->id,
            'amount' => $request->amount,
            'status' => 0,
            'withdrawal_date' => now(),
        ]);
        return response()->json(['message' => 'Withdraw request submitted successfully'], 200);
    }
}
