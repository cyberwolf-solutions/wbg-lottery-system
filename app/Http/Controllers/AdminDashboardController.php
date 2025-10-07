<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Deposit;
use App\Models\DigitResult;
use App\Models\Message;
use App\Models\Results;
use App\Models\Lotteries;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    public function index()
    {
        Log::info('Admin Dashboard index method accessed');

        // Get lottery statistics
        $lotteries = Lotteries::withCount(['dashboards', 'digitDashboards', 'winners', 'digitwinners'])
            ->with([
                'results' => function ($query) {
                    $query->latest()->take(5);
                },
                'digitDashboards' => function ($query) {
                    $query->latest()->take(5); // optional: limit recent digit dashboards
                }
            ])
            ->get();


        Log::info('Lottery statistics retrieved', ['lotteries' => $lotteries]);

        // Get user statistics
        $userStats = [
            'total' => User::count(),
            'new_today' => User::whereDate('created_at', today())->count(),
            'active' => User::has('pickedNumbers')->count(),
        ];

        Log::info('User statistics retrieved', ['userStats' => $userStats]);

        // Get recent results
        $recentResults = Results::with(['lottery', 'dashboard'])
            ->latest()
            ->take(5)
            ->get();

        $recentDigitResults = DigitResult::with(['lottery', 'dashboard'])
            ->latest()
            ->take(5)
            ->get();

        // Log::info('Recent results retrieved', ['recentResults' => $recentResults]);

        // Get jackpot information
        // $jackpots = Lotteries::with(['results' => function($query) {
        //         $query->select('lottery_id', 'winning_number')
        //             ->latest()
        //             ->take(1);
        //     }])
        //     ->get()
        //     ->map(function($lottery) {
        //         return [
        //             'id' => $lottery->id,
        //             'name' => $lottery->name,
        //             'color' => $lottery->color,
        //             'winning_number' => $lottery->results->first()->winning_number ?? 'N/A',
        //             'image' => $lottery->image,
        //         ];
        //     });

        // Log::info('Jackpot information retrieved', ['jackpots' => $jackpots]);

        $latestMessages = Message::latest()->take(5)->with('user')->get();
        $latestWithdraws = Withdrawal::latest()->take(5)->with('user')->get();
        $latestDeposits = Deposit::latest()->take(5)->with('user')->get();

        log::info('Latest messages retrieved', ['latestMessages' => $latestMessages]);
        log::info('Latest withdraws retrieved', ['latestWithdraws' => $latestWithdraws]);
        log::info('Latest deposits retrieved', ['latestDeposits' => $latestDeposits]);

        return Inertia::render('AdminDashboard/Dashboard', [
            'lotteries' => $lotteries,
            'userStats' => $userStats,
            'recentResults' => $recentResults,
            'recentDigitResults' => $recentDigitResults,
            'latestMessages' => $latestMessages,
            'latestWithdraws' => $latestWithdraws,
            'latestDeposits' => $latestDeposits,
        ]);
    }
}
