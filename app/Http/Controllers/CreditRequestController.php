<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Deposit;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Notifications\DepositStatusNotification;

class CreditRequestController extends Controller
{
    //
    public function index()
    {
        $credits = Deposit::with('wallet.user')
        ->orderBy('deposit_date', 'desc')
        ->get();

        // dd($credits);
        // Log::info('Fetched Deposits:', ['credits' => $credits]);

        return Inertia::render('AdminDashboard/Credit', [
            'credits' => $credits,
            'status' => session('status'),
        ]);
    }


    public function approve(Request $request, $id)
    {
        $deposit = Deposit::findOrFail($id);

        
        if ($deposit->status == 1) {
            return response()->json(['message' => 'Already approved'], 400);
        }

        // Update deposit status
        $deposit->status = 1;
        $deposit->save();

        // Add deposit amount to the wallet balance
        $wallet = $deposit->wallet;
        if ($wallet) {
            $wallet->available_balance += $deposit->amount;
            $wallet->save();
        }

        Transaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $deposit->amount,
            'type' => 'Deposit',
            'lottery_id' => null,
            'lottery_dashboard_id' => null,
            'transaction_date' => Carbon::now(),
            'picked_number' => null
        ]);

        $user = $wallet->user;
        if ($user) {
            $user->notify(new DepositStatusNotification('approved', $deposit->amount));
            Log::info('Deposit approval notification sent', [
                'user_id' => $user->id,
                'deposit_id' => $deposit->id,
                'amount' => $deposit->amount
            ]);
        }

        return response()->json(['message' => 'Deposit approved successfully']);
    }

    public function decline(Request $request, $id)
    {
        $deposit = Deposit::findOrFail($id);
        $declineReason = $request->reason ?? 'No reason provided';

        $deposit->update([
            'status' => 2,
            'decline_reason' => $declineReason,
        ]);

        $user = $deposit->wallet->user;
        if ($user) {
            $user->notify(new DepositStatusNotification('declined', $deposit->amount, $declineReason));
            Log::info('Deposit decline notification sent', [
                'user_id' => $user->id,
                'deposit_id' => $deposit->id,
                'amount' => $deposit->amount,
                'reason' => $declineReason
            ]);
        }

        // $deposit->delete();

        return response()->json(['message' => 'Deposit request declined successfully.']);
    }
}
