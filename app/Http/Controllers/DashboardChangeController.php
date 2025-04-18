<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Holiday;
use App\Models\Lotteries;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DashboardChangeController extends Controller
{
    //
    public function index()
    {
        $lotteries = Lotteries::with(['dashboards' => function ($query) {
            $query->where('status', 'active')
                ->orderBy('date', 'asc')
                ->orderBy('created_at', 'desc');
        }])->get();


        $lotteries->each(function ($lottery) {
            $uniqueDashboards = collect();
            $seen = [];

            foreach ($lottery->dashboards as $dashboard) {

                $key = $dashboard->lottery_id . '_' .
                    $dashboard->dashboard . '_' .
                    $dashboard->price . '_' .
                    $dashboard->date . '_' .
                    $dashboard->draw . '_' .
                    $dashboard->draw_number;

                if (!isset($seen[$key])) {
                    $seen[$key] = true;

                    $uniqueDashboards->push($dashboard);
                }
            }

            $lottery->setRelation('dashboards', $uniqueDashboards);
        });

        $holidays = Holiday::all()->map(function ($holiday) {
            return $holiday->date->format('Y-m-d');
        });

        return Inertia::render('AdminDashboard/Edit', [
            'lotteries' => $lotteries,
            'holidays' => $holidays,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        try {
            $primaryDashboard = LotteryDashboards::findOrFail($id);
            $originalDate = $primaryDashboard->date;
            $newDate = $request->date;


            $relatedDashboards = LotteryDashboards::where('lottery_id', $primaryDashboard->lottery_id)
                ->where('draw_number', $primaryDashboard->draw_number)
                ->get();


            foreach ($relatedDashboards as $dash) {
                $dash->update(['date' => $newDate]);
            }


            $subsequentDashboards = LotteryDashboards::where('lottery_id', $primaryDashboard->lottery_id)
                ->where('draw_number', '>', $primaryDashboard->draw_number)
                ->orderBy('draw_number')
                ->get();

            $currentDate = Carbon::parse($newDate);
            $nextDrawNumber = $primaryDashboard->draw_number + 1;

            foreach ($subsequentDashboards->groupBy('draw_number') as $drawNumber => $dashboardsForDraw) {
                $currentDate = $this->getNextValidDate($currentDate->copy()->addDay());

                foreach ($dashboardsForDraw as $dashboard) {
                    $dashboard->update([
                        'date' => $currentDate->format('Y-m-d'),
                        'draw_number' => $nextDrawNumber
                    ]);
                }

                $nextDrawNumber++;
            }

            return response()->json([
                'success' => true,
                'message' => 'Dashboard and related draws updated successfully',
            ]);
        } catch (\Exception $e) {
            Log::error("Error updating dashboard dates", [
                'dashboard_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    protected function getNextValidDate(Carbon $date)
{
    $originalDate = $date->copy();

    while (Holiday::where('date', $date->format('Y-m-d'))->exists()) {
        Log::debug("Skipping holiday", [
            'date' => $date->format('Y-m-d'),
            'is_holiday' => true
        ]);

        $date->addDay();
    }

    if (!$originalDate->eq($date)) {
        Log::info("Adjusted date due to holiday", [
            'original_date' => $originalDate->format('Y-m-d'),
            'adjusted_date' => $date->format('Y-m-d')
        ]);
    }

    return $date;
}

}
