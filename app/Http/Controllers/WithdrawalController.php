<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Notifications\WithdrawalStatusNotification;

class WithdrawalController extends Controller
{
    //
    public function index()
    {
        // Fetch the withdrawals with the related wallet and user data
        $credits = Withdrawal::with('wallet.user')->get();

        // Pass withdrawals to the Inertia view
        return Inertia::render('AdminDashboard/withdraw', [

            'status' => session('status'),
            'credits' => $credits,
        ]);
    }

    public function approve(Request $request, $id)
    {
        $deposit = Withdrawal::findOrFail($id);
        Log::info("Approving withdrawal", ['id' => $id]);

        if ($deposit->status == 1) {
            return response()->json(['message' => 'Already approved'], 400);
        }

        Log::info('Number', ['pickednumber' => $id]);

        // dd($request->all);

        // Update deposit status
        $deposit->status = 1;
        $deposit->save();

        // Add deposit amount to the wallet balance
        $wallet = $deposit->wallet;
        if ($wallet) {
            $wallet->available_balance -= $deposit->amount;
            $wallet->save();
        }

        Transaction::create([
            'wallet_id' => $wallet->id,
            'amount' => -$deposit->amount,
            'type' => 'Withdrawal',
            'lottery_id' => null,
            'lottery_dashboard_id' => null,
            'transaction_date' => Carbon::now(),
            'picked_number' => null
        ]);

        $user = $wallet->user;
        if ($user) {
            $user->notify(new WithdrawalStatusNotification('approved', $deposit->amount));
            Log::info('Withdrawal approval notification sent', [
                'user_id' => $user->id,
                'withdrawal_id' => $deposit->id,
                'amount' => $deposit->amount
            ]);
        }

        return response()->json(['message' => 'Withdrawal approved successfully']);
    }
    public function decline(Request $request, $id)
    {
        $deposit = Withdrawal::findOrFail($id);
        $declineReason = $request->reason ?? 'No reason provided';

        $deposit->update([
            'status' => 2,
            'decline_reason' => $request->reason ?? 'No reason provided',
        ]);


        $user = $deposit->wallet->user;
        if ($user) {
            $user->notify(new WithdrawalStatusNotification('declined', $deposit->amount, $declineReason));
            Log::info('Withdrawal decline notification sent', [
                'user_id' => $user->id,
                'withdrawal_id' => $deposit->id,
                'amount' => $deposit->amount,
                'reason' => $declineReason
            ]);
        }
    
        // $deposit->delete();

        return response()->json(['message' => 'Withdrawal request declined successfully.']);
    }
}
