<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FundsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with('wallet')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->get(); 

        return Inertia::render('AdminDashboard/Funds', [
            'users' => $users,
        ]);
    }

    public function updateWallet(Request $request, $userId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:add,deduct',
            'notes' => 'nullable|string|max:255'
        ]);

        $user = User::findOrFail($userId);
        $wallet = $user->wallet()->firstOrCreate(['user_id' => $userId]);

        $amount = $request->amount;
        $type = $request->type;

        if ($type === 'add') {
            $wallet->increment('available_balance', $amount);
            $transactionType = 'Admin Deposit';
        } else {
            if ($wallet->available_balance < $amount) {
                return back()->withErrors(['amount' => 'Insufficient balance for deduction']);
            }
            $wallet->decrement('available_balance', $amount);
            $transactionType = 'Admin Deduction';
        }

        // Create transaction record
        Transaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $amount,
            'type' => $transactionType,
            'transaction_date' => now(),
            'notes' => $request->notes
        ]);

        return response()->json(['success' => 'Wallet updated successfully'], 200);
    }
}
