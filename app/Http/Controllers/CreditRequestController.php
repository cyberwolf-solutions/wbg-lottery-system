<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CreditRequestController extends Controller
{
    //
    public function index()
    {
        $credits = Deposit::with('wallet.user')->get();

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

        return response()->json(['message' => 'Deposit approved successfully']);
    }

    public function decline(Request $request, $id)
    {
        $deposit = Deposit::findOrFail($id);

        $deposit->update([
            'status' => 2,
            'decline_reason' => $request->reason ?? 'No reason provided',
        ]);

        // $deposit->delete();

        return response()->json(['message' => 'Deposit request declined successfully.']);
    }
}
