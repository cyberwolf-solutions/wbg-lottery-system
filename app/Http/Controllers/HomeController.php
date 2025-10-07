<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\Deposit;
use App\Models\Notices;
use App\Models\Withdrawal;
use App\Models\PickedNumber;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
use App\Http\Controllers\Controller;
use App\Models\DigitPickedNumber;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function dashboard()
    {
        $purchaseHistory = PickedNumber::with('lotteryDashboard.lottery')
            ->where('user_id', Auth::id())
            ->get();
        $digitpurchaseHistory = DigitPickedNumber::with('lotteryDashboard.lottery')
            ->where('user_id', Auth::id())
            ->get();

        // Ensure wallet exists or create a default one
        $wallet = Wallet::firstOrCreate(
            ['user_id' => Auth::id()],
            ['available_balance' => 0]
        );


        $lotteryDashboards = LotteryDashboards::with('lottery')
            ->where('status', 'active')
            ->get();

        $deposits = Deposit::where('wallet_id', $wallet->id)
            ->selectRaw('SUM(amount) as total_amount, COUNT(*) as count')
            ->where('status', '1')
            ->first();

        // Get withdrawal summary
        $withdrawals = Withdrawal::where('wallet_id', $wallet->id)
            ->selectRaw('SUM(amount) as total_amount, COUNT(*) as count')
            ->where('status', '1')
            ->first();


        $allNotices = Notices::where('user_id', Auth::id())
            ->orWhereNull('user_id')
            ->latest()
            ->paginate(2);


        // dd($deposits);
        return Inertia::render('Dashboard', [
            'purchaseHistory' => $purchaseHistory,
            'digitpurchaseHistory' => $digitpurchaseHistory,
            'wallet' => $wallet,
            'lotteryDashboards' => $lotteryDashboards,
            'pickedNumbers' => [],
            'deposits' => [
                'total_amount' => $deposits->total_amount ?? 0,
                'count' => $deposits->count ?? 0
            ],
            'withdrawals' => [
                'total_amount' => $withdrawals->total_amount ?? 0,
                'count' => $withdrawals->count ?? 0
            ],
            'allNotices' => $allNotices,
            'authUser' => Auth::user(),
        ]);
    }
}
