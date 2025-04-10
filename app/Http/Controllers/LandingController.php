<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Winner;
use App\Models\Results;
use App\Models\Lotteries;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
use App\Http\Controllers\Controller;

class LandingController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        
        $upcomingDraws = LotteryDashboards::with(['lottery', 'pickedNumbers'])
            ->where('date', '>=', $now)
            ->orderBy('date', 'asc')
            ->take(2)
            ->get();
       
        $lotteriesData = $upcomingDraws->map(function ($draw) {
            
            $pickedCount = $draw->pickedNumbers->count();
            $soldPercentage = min(100, ($pickedCount / 100) * 100); 

            return [
                'id' => $draw->lottery_id,
                'name' => $draw->lottery->name,
                'image' => $draw->lottery->image,
                'prize' => $draw->price,
                'price_per_ticket' => $draw->price / 100, 
                'draw_time' => Carbon::parse($draw->date)->setHour(20)->setMinute(0)->setSecond(0)->toIso8601String(),
                'sold_percentage' => $soldPercentage,
                'picked_count' => $pickedCount,
                'total_numbers' => 100 
            ];
        });


        $lotteries = Lotteries::with(['dashboards' => function ($query) use ($now) {
            $query->where('date', '>=', $now)
                ->orderBy('date', 'asc'); // Get the next draw
        }])->take(3)->get();

        // Process each lottery to find its closest draw at 8 PM
        $lotteriesData = $lotteries->map(function ($lottery) {
            $closestDraw = $lottery->dashboards->first();

            return [
                'id' => $lottery->id,
                'name' => $lottery->name,
                'image' => $lottery->image,
                'description' => $lottery->description,
                'prize' => $closestDraw ? $closestDraw->price : 'N/A',
                'draw_time' => $closestDraw
                    ? Carbon::parse($closestDraw->date)->setHour(20)->setMinute(0)->setSecond(0)->toIso8601String()
                    : null,
            ];
        });

        // Fetch the result
        $dashboardDetails = LotteryDashboards::select('price', 'date', 'draw_number', 'draw')
            ->distinct()
            ->get();

        $winningNumbers = [];

        foreach ($dashboardDetails as $details) {
            // Find matching dashboards with "First Digits" and "Last Digits"
            $matchingDashboards = LotteryDashboards::where([
                ['price', '=', $details->price],
                ['date', '=', $details->date],
                ['draw_number', '=', $details->draw_number],
                ['draw', '=', $details->draw]
            ])
                ->whereIn('dashboardType', ['First Digits', 'Last Digits'])
                ->pluck('id');

            // Get the winning numbers for these dashboards
            $results = Results::whereIn('dashboard_id', $matchingDashboards)
                ->pluck('winning_number', 'dashboard_id');

            // Get the lottery name
            $lottery = Lotteries::whereHas('dashboards', function ($query) use ($details) {
                $query->where([
                    ['price', '=', $details->price],
                    ['date', '=', $details->date],
                    ['draw_number', '=', $details->draw_number],
                    ['draw', '=', $details->draw]
                ]);
            })->first();

            // Store results grouped by dashboard details and include the lottery name
            if (!$results->isEmpty()) {
                $winningNumbers[] = [
                    'lottery_name' => $lottery ? $lottery->name : 'Unknown Lottery',
                    'price' => $details->price,
                    'date' => $details->date,
                    'draw_number' => $details->draw_number,
                    'draw' => $details->draw,
                    'winning_numbers' => $results
                ];
            }
        }


        $winners = Winner::with(['lottery', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();



        // dd($winners);
        // Get the closest upcoming draw date
        $closestDraw = LotteryDashboards::where('date', '>=', $now)
            ->orderBy('date', 'asc')
            ->first();



        // Ensure the draw time is set to 8 PM (20:00:00)
        $closestDrawDate = $closestDraw
            ? Carbon::parse($closestDraw->date)->setHour(20)->setMinute(0)->setSecond(0)
            : null;



            // dd($winningNumbers);

        
        // dd($closestDrawDate ? $closestDrawDate->toIso8601String() : null);
        return Inertia::render('LandingPage', [
            'latestDraw' => $closestDrawDate ? $closestDrawDate->toIso8601String() : null,
            'lotteries' => $lotteriesData,
            'winning_numbers' => $winningNumbers,
            'winners' => $winners->map(function ($winner) {
                return [
                    'name' => $winner->user->name,
                    'lottery' => $winner->lottery->name,
                    'draw_date' => Carbon::parse($winner->created_at)->toDateString(),
                    'amount' => $winner->price,
                ];
            }),
            'upcomingDraws' => $lotteriesData,
        ]);
    }
}
