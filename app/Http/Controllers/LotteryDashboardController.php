<?php

namespace App\Http\Controllers;

use the;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Lotteries;
use App\Jobs\PickNumberJob;
use App\Models\PickedNumber;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LotteryDashboardController extends Controller
{
    //
    public function index($id)
    {
        $lottery = Lotteries::find($id);

        // dd($lotteries);
        $lot = $lottery->toArray();
        $dashboard = LotteryDashboards::where('lottery_id', $id)->get();

        // dd($dashboard);

        return Inertia::render('AdminDashboard/Lotteries', [
            'lotteries' => $lottery,
            'dashboards' => $dashboard,
        ]);
    }
    public function store(Request $request)
    {
        try {
            Log::info($request);

            // Validate the incoming data
            $validated = $request->validate([
                'price' => 'required|numeric',
                'date' => 'required|date',
                'draw' => 'required|string',
                'drawNumber' => 'required|numeric',
                'lottery_id' => 'required|exists:lotteries,id',
                'dashboard' => 'required|string',
                'dashboardType'=>'required|string',
                // 'color' => 'required|string'
            ]);

            // Calculate the start date
            $startDate = Carbon::parse($validated['date']);
            $drawNumber = $validated['drawNumber'];

            // Generate the winning numbers (00, 01, 02, ..., 99)
            $winningNumbers = $this->generateWinningNumbers();

            // Create 10 dashboards (1 for the current day and 9 for the next 9 days)
            $dashboards = [];
            for ($i = 0; $i < 10; $i++) {
                $currentDate = $startDate->addDay(); // Increment the date for the next day
                $dashboards[] = LotteryDashboards::create([
                    'price' => $validated['price'],
                    'date' => $currentDate->toDateString(),  // Convert to a date string format (YYYY-MM-DD)
                    'draw' => $drawNumber,
                    'draw_number' => str_pad($drawNumber++, 3, '0', STR_PAD_LEFT),
                    'winning_numbers' => json_encode($winningNumbers),
                    'lottery_id' => $validated['lottery_id'],
                    'dashboard' => $validated['dashboard'],
                    // 'color'=>$validated['color'],
                    'status' => 'active',
                    'dashboardType' =>$validated['dashboardType']
                ]);
            }

            // Return the response with created dashboards
            return response()->json([
                'message' => 'Dashboards created successfully!',
                'dashboards' => $dashboards,
            ], 201);
        } catch (\Exception $e) {
            // Log the error details
            Log::error('Error creating Lottery Dashboards:', [
                'error_message' => $e->getMessage(),
                'error_stack' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            // Return a response with the error
            return response()->json([
                'message' => 'There was an error creating the dashboards. Please try again later.',
            ], 500);
        }
    }

    private function generateWinningNumbers()
    {
        $numbers = [];
        for ($i = 0; $i < 100; $i++) {
            $numbers[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        return $numbers;
    }
}
