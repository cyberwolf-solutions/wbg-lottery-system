<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\Winner;
use App\Models\Deposit;
use App\Models\Affiliate;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\WalletAdress;
use Illuminate\Http\Request;
use App\Mail\CreditRequestMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
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

        $winnings = Winner::with(['lottery', 'lotteryDashboard'])
            ->where('user_id', Auth::id())
            ->join('lottery_dashboards', 'winners.lottery_dashboard_id', '=', 'lottery_dashboards.id')
            ->orderBy('lottery_dashboards.date', 'desc')
            ->get();

        // dd($winnings);



        $transaction = Transaction::with('lottery', 'lotteryDashboard')
            ->whereHas('wallet', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->get();


        $withdrawal = Withdrawal::whereHas('wallet', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->orderBy('created_at', 'desc')
            ->get();

        $deposit = Deposit::whereHas('wallet', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($deposit);

        $bank = Bank::all();
        $walletAddress = WalletAdress::all();

        // $refund = Transaction::where('type', 'refund')
        //     ->where('waller_id',)->get();

        // dd($wallet);
        // Get all affiliate records where the current user is the referrer
        $affiliateData = Affiliate::where('user_id', Auth::id())
            ->with(['affiliateUser' => function ($query) {
                // You can customize what user data to load
                $query->select('id', 'name', 'email', 'created_at');
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($affiliate) {
                return [
                    'id' => $affiliate->id,
                    'affiliate_user' => $affiliate->affiliateUser, // This contains the user data
                    'price' => $affiliate->price,
                    'date' => $affiliate->date,
                    // Add any other affiliate fields you need
                ];
            });
        // dd($affiliateData);

        return Inertia::render('User/Wallet', [
            'status' => session('status'),
            'wallet' => $wallet,
            'transactions' => $transaction,
            'withdrawal' => $withdrawal,
            'deposit' => $deposit,
            'bank' => $bank,
            'walletAddress' => $walletAddress,
            'winnings' =>  $winnings,
            'affiliateData' => $affiliateData
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
            'attachment' => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            'deposit_type' => 'string'
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
            $imagePath = 'images/request/' . $imageName;
        }

        // Save deposit request
        $deposit = Deposit::create([
            'wallet_id' => Auth::user()->wallet?->id,
            'amount' => $request->amount,
            'deposite_type' => $request->deposit_type,
            'reference' => $request->reference,
            'deposit_date' => now(),
            'image' => $imagePath ?? null,
            'status' => 0,

        ]);
        try {
            $details = [
                'amount' => $request->amount,
                'bank' => $request->bank,
                'account_number' => $request->account_number,
                'reference' => $request->reference,
                'deposit_type' => $request->deposit_type,
                'image' => $imagePath,
            ];
            $userName = Auth::user()->name ?? 'Unknown User';

            Mail::to('takeprofitone@gmail.com')->send(new CreditRequestMail('Deposit', $details, $userName));

            Log::info('Deposit request email sent successfully.', ['deposit_id' => $deposit->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send deposit request email.', [
                'error' => $e->getMessage(),
                'deposit_id' => $deposit->id
            ]);
        }
        return response()->json(['message' => 'Deposit request submitted successfully'], 200);
    }

    public function withdraw(Request $request)
    {
        Log::info("Request Data: ", $request->all());
        $validator = Validator::make($request->all(), [
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:1',
            'withdrawal_type' => 'required|String',
            'wallet_address' => 'required|String'
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



        // $wallet = Wallet::find($request->wallet_id);
        $withdrawal = Withdrawal::create([
            'wallet_id' => Auth::user()->wallet?->id,
            'amount' => $request->amount,
            'status' => 0,
            'withdrawal_date' => now(),
            'withdrawal_type' => $request->withdrawal_type,
            'address' => $request->wallet_address
        ]);

        try {
            $details = [
                'amount' => $request->amount,
                'withdrawal_type' => $request->withdrawal_type,
                'wallet_address' => $request->wallet_address,
            ];
            $userName = Auth::user()->name ?? 'Unknown User';

            // Log before attempting to send the email
            Log::info('Attempting to send withdrawal request email.', [
                'withdrawal_id' => $withdrawal->id,
                'to' => 'takeprofitone@gmail.com',
                'details' => $details,
                'user_name' => $userName
            ]);

            Mail::to('takeprofitone@gmail.com')->send(new CreditRequestMail('Withdrawal', $details, $userName));

            Log::info('Withdrawal request email sent successfully.', ['withdrawal_id' => $withdrawal->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send withdrawal request email.', [
                'error' => $e->getMessage(),
                'withdrawal_id' => $withdrawal->id
            ]);
        }
        return response()->json(['message' => 'Withdraw request submitted successfully'], 200);
    }
}
