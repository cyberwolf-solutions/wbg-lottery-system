<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Results;
use App\Models\Lotteries;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Log;

class LatestResults extends Controller
{
    public function index()
    {
        // Fetch the result
        $dashboardDetails = LotteryDashboards::select('price', 'date', 'draw_number', 'draw')
            ->distinct()
            ->get();

        $formattedLotteries = [];

        foreach ($dashboardDetails as $details) {
            // Find matching dashboards with "First Digits" and "Last Digits"
            $matchingDashboards = LotteryDashboards::where([
                ['price', '=', $details->price],
                ['date', '=', $details->date],
                ['draw_number', '=', $details->draw_number],
                ['draw', '=', $details->draw]
            ])
                ->whereIn('dashboardType', ['First Digits', 'Last Digits'])
                ->get();

            // Get the winning numbers for these dashboards
            $results = Results::whereIn('dashboard_id', $matchingDashboards->pluck('id'))
                ->get()
                ->keyBy('dashboard_id');

            // Get the lottery
            $lottery = Lotteries::whereHas('dashboards', function ($query) use ($details) {
                $query->where([
                    ['price', '=', $details->price],
                    ['date', '=', $details->date],
                    ['draw_number', '=', $details->draw_number],
                    ['draw', '=', $details->draw]
                ]);
            })->first();

            // Process each matching dashboard to format data for the frontend
            foreach ($matchingDashboards as $dashboard) {
                $winningNumber = $results->get($dashboard->id)?->winning_number;
                
                if ($winningNumber) {
                    $formattedLotteries[] = [
                        'id' => $dashboard->id,
                        'name' => $lottery ? $lottery->name : 'Unknown Lottery',
                        'image' => $lottery ? $lottery->image : 'default-image.png',
                        'dashboardInfo' => [
                            'dashboardType' => $dashboard->dashboardType,
                            'draw_number' => $dashboard->draw_number,
                            'date' => $dashboard->date,
                            'price' => $dashboard->price,
                            'draw' => $dashboard->draw,
                            'displayDigits' => [
                                'digits' => str_split($winningNumber),
                                'position' => str_contains($dashboard->dashboardType, 'First') ? 'First' : 'Last'
                            ]
                        ]
                    ];
                }
            }
        }

        return Inertia::render('User/LatestResults', [
            'lotteries' => $formattedLotteries,
            'status' => session('status'),
        ]);
    }
}