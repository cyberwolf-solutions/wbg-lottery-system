<?php

namespace App\Http\Controllers;

use App\Models\Lotteries;
use App\Models\User;
use App\Models\Results;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        Log::info('Admin Dashboard index method accessed');

        // Get lottery statistics
        $lotteries = Lotteries::withCount(['dashboards', 'winners'])
            ->with(['results' => function($query) {
                $query->latest()->take(5);
            }])
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

        Log::info('Recent results retrieved', ['recentResults' => $recentResults]);

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

        return Inertia::render('AdminDashboard/Dashboard', [
            'lotteries' => $lotteries,
            'userStats' => $userStats,
            'recentResults' => $recentResults,
            // 'jackpots' => $jackpots,
        ]);
    }
}