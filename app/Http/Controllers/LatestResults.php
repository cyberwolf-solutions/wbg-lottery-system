<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Results;
use App\Models\Lotteries;
use App\Models\LotteryDashboards;
use App\Models\DigitLotteryDashboard;
use App\Models\DigitResult;
use Illuminate\Http\Request;

class LatestResults extends Controller
{
    public function index()
    {
        $grouped = [];
        $formattedLotteries = [];

        /**
         * -------------------------
         *  Normal Lottery Dashboards
         * -------------------------
         */
        $dashboardDetails = LotteryDashboards::select('price', 'date', 'draw_number', 'draw')
            ->distinct()
            ->get();

        foreach ($dashboardDetails as $details) {
            $matchingDashboards = LotteryDashboards::where([
                ['price', '=', $details->price],
                ['date', '=', $details->date],
                ['draw_number', '=', $details->draw_number],
                ['draw', '=', $details->draw]
            ])
                ->whereIn('dashboardType', ['First Digits', 'Last Digits'])
                ->get();

            $results = Results::whereIn('dashboard_id', $matchingDashboards->pluck('id'))
                ->get()
                ->keyBy('dashboard_id');

            $lottery = Lotteries::whereHas('dashboards', function ($query) use ($details) {
                $query->where([
                    ['price', '=', $details->price],
                    ['date', '=', $details->date],
                    ['draw_number', '=', $details->draw_number],
                    ['draw', '=', $details->draw]
                ]);
            })->first();

            if ($lottery) {
                foreach ($matchingDashboards as $dashboard) {
                    $winningNumber = $results->get($dashboard->id)?->winning_number;

                    if ($winningNumber) {
                        $key = $lottery->id . '_' . $details->date . '_' . $details->draw_number . '_' . $details->price . '_' . $details->draw . '_' . $dashboard->dashboardType;

                        $grouped[$key] = [
                            'lottery_id' => $lottery->id,
                            'name' => $lottery->name,
                            'image' => $lottery->image,
                            'date' => $details->date,
                            'draw_number' => $details->draw_number,
                            'price' => $details->price,
                            'draw' => $details->draw,
                            'dashboardType' => $dashboard->dashboardType,
                            'normalDigits' => str_split($winningNumber),
                            'digitDigits' => $grouped[$key]['digitDigits'] ?? null,
                        ];
                    }
                }
            }
        }

        /**
         * -------------------------
         *  Digit Lottery Dashboards
         * -------------------------
         */
        $digitDashboardDetails = DigitLotteryDashboard::select('price', 'date', 'draw_number', 'draw')
            ->distinct()
            ->get();

        foreach ($digitDashboardDetails as $details) {
            $matchingDashboards = DigitLotteryDashboard::where([
                ['price', '=', $details->price],
                ['date', '=', $details->date],
                ['draw_number', '=', $details->draw_number],
                ['draw', '=', $details->draw]
            ])
                ->whereIn('dashboardType', ['First Digits', 'Last Digits'])
                ->get();

            $results = DigitResult::whereIn('digit_dashboard_id', $matchingDashboards->pluck('id'))
                ->get()
                ->keyBy('digit_dashboard_id');

            $lottery = Lotteries::whereHas('digitDashboards', function ($query) use ($details) {
                $query->where([
                    ['price', '=', $details->price],
                    ['date', '=', $details->date],
                    ['draw_number', '=', $details->draw_number],
                    ['draw', '=', $details->draw]
                ]);
            })->first();

            if ($lottery) {
                foreach ($matchingDashboards as $dashboard) {
                    $winningNumber = $results->get($dashboard->id)?->winning_number;

                    if ($winningNumber) {
                        $key = $lottery->id . '_' . $details->date . '_' . $details->draw_number . '_' . $details->price . '_' . $details->draw . '_' . $dashboard->dashboardType;

                        if (isset($grouped[$key])) {
                            $grouped[$key]['digitDigits'] = str_split($winningNumber);
                        } else {
                            $grouped[$key] = [
                                'lottery_id' => $lottery->id,
                                'name' => $lottery->name,
                                'image' => $lottery->image,
                                'date' => $details->date,
                                'draw_number' => $details->draw_number,
                                'price' => $details->price,
                                'draw' => $details->draw,
                                'dashboardType' => $dashboard->dashboardType,
                                'normalDigits' => null,
                                'digitDigits' => str_split($winningNumber),
                            ];
                        }
                    }
                }
            }
        }

        // Convert grouped to formattedLotteries with unique id for Vue key
        $counter = 1;
        foreach ($grouped as $key => $group) {
            $position = str_contains($group['dashboardType'], 'First') ? 'First' : 'Last';
            $formattedLotteries[] = [
                'id' => $counter++, // Unique incremental ID for Vue key
                'name' => $group['name'] ?? 'Unknown Lottery',
                'image' => $group['image'] ?? 'default-image.png',
                'dashboardInfo' => [
                    'dashboardType' => $group['dashboardType'],
                    'draw_number' => $group['draw_number'],
                    'date' => $group['date'],
                    'price' => $group['price'],
                    'draw' => $group['draw'],
                    'normalDigits' => $group['normalDigits'],
                    'digitDigits' => $group['digitDigits'],
                    'position' => $position,
                ]
            ];
        }

        return Inertia::render('User/LatestResults', [
            'lotteries' => $formattedLotteries,
            'status' => session('status'),
        ]);
    }
}
