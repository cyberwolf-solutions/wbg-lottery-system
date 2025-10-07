<?php

namespace App\Http\Controllers;

use the;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Holiday;
use App\Models\Lotteries;
use App\Jobs\PickNumberJob;
use App\Models\PickedNumber;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\DigitLotteryDashboard;
use Illuminate\Support\Facades\Auth;

class LotteryDashboardController extends Controller
{
    //
    public function index($id)
    {
        $lottery = Lotteries::find($id);

        // dd($lotteries);
        $lot = $lottery->toArray();
        $dashboard = LotteryDashboards::where('lottery_id', $id)
            ->orderBy('date', 'desc')
            ->get();


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
                'lottery_id' => 'required|exists:lotteries,id',
                'dashboard' => 'required|string',
                'drawNumber' => 'required'
            ]);

            Log::info('ok', $request->all());

            // Get the last created draw and draw_number
            $lastDashboard = LotteryDashboards::where('lottery_id', $validated['lottery_id'])
                ->where('dashboard', $validated['dashboard'])
                ->where('price', $validated['price'])
                ->orderBy('draw', 'desc')
                ->orderBy('draw_number', 'desc')
                ->first();

            // If no previous draw exists, start from 1
            $draw = 1;
            $drawNumber = (int)$validated['drawNumber'];

            // Calculate the start date
            $startDate = Carbon::parse($validated['date']);

            // Generate the winning numbers (00, 01, 02, ..., 99)
            $winningNumbers = $this->generateWinningNumbers();

            // Create 10 dashboards for "First Digits" and 10 for "Last Digits"
            $createdDays = 0;
            while ($createdDays < 10) {
                $startDate->addDay();

                // Check if this date is a holiday
                $isHoliday = Holiday::where('date', $startDate->toDateString())->exists();
                if ($isHoliday) {
                    Log::info("Skipping dashboard creation on holiday: " . $startDate->toDateString());
                    continue;
                }

                $currentDrawNumber = str_pad($drawNumber, 3, '0', STR_PAD_LEFT);

                // First Digits Dashboard
                $dashboards[] = LotteryDashboards::create([
                    'price' => $validated['price'],
                    'date' => $startDate->toDateString(),
                    'draw' => $draw,
                    'draw_number' => $currentDrawNumber,
                    'winning_numbers' => json_encode($winningNumbers),
                    'lottery_id' => $validated['lottery_id'],
                    'dashboard' => $validated['dashboard'],
                    'status' => 'active',
                    'dashboardType' => 'First Digits',
                ]);

                // Last Digits Dashboard
                $dashboards[] = LotteryDashboards::create([
                    'price' => $validated['price'],
                    'date' => $startDate->toDateString(),
                    'draw' => $draw,
                    'draw_number' => $currentDrawNumber,
                    'winning_numbers' => json_encode($winningNumbers),
                    'lottery_id' => $validated['lottery_id'],
                    'dashboard' => $validated['dashboard'],
                    'status' => 'active',
                    'dashboardType' => 'Last Digits',
                ]);


                $draw++;
                $drawNumber++;
                $createdDays++;
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



    public function OneDigit($id)
    {
        $lottery = Lotteries::find($id);

        // dd($lotteries);
        $lot = $lottery->toArray();
        $dashboard = DigitLotteryDashboard::where('lottery_id', $id)
            ->orderBy('date', 'desc')
            ->get();


        // dd($dashboard);

        return Inertia::render('AdminDashboard/digitDashboard', [
            'lotteries' => $lottery,
            'dashboards' => $dashboard,
        ]);
    }



    public function digitstore(Request $request)
    {
        try {
            Log::info($request);

            // Validate the incoming data
            $validated = $request->validate([
                'price' => 'required|numeric',
                'date' => 'required|date',
                'lottery_id' => 'required|exists:lotteries,id',
                'dashboard' => 'required|string',
                'drawNumber' => 'required'
            ]);

            Log::info('ok', $request->all());

            // Get the last created draw and draw_number
            $lastDashboard = DigitLotteryDashboard::where('lottery_id', $validated['lottery_id'])
                ->where('dashboard', $validated['dashboard'])
                ->where('price', $validated['price'])
                ->orderBy('draw', 'desc')
                ->orderBy('draw_number', 'desc')
                ->first();

            // If no previous draw exists, start from 1
            $draw = 1;
            $drawNumber = (int)$validated['drawNumber'];

            // Calculate the start date
            $startDate = Carbon::parse($validated['date']);

            // Generate the winning numbers (00, 01, 02, ..., 99)
            $winningNumbers = $this->digitgenerateWinningNumbers();

            // Create 10 dashboards for "First Digits" and 10 for "Last Digits"
            $createdDays = 0;
            while ($createdDays < 10) {
                $startDate->addDay();

                // Check if this date is a holiday
                $isHoliday = Holiday::where('date', $startDate->toDateString())->exists();
                if ($isHoliday) {
                    Log::info("Skipping dashboard creation on holiday: " . $startDate->toDateString());
                    continue;
                }

                $currentDrawNumber = str_pad($drawNumber, 3, '0', STR_PAD_LEFT);

                // First Digits Dashboard
                $dashboards[] = DigitLotteryDashboard::create([
                    'price' => $validated['price'],
                    'date' => $startDate->toDateString(),
                    'draw' => $draw,
                    'draw_number' => $currentDrawNumber,
                    'winning_numbers' => json_encode($winningNumbers),
                    'lottery_id' => $validated['lottery_id'],
                    'dashboard' => $validated['dashboard'],
                    'status' => 'active',
                    'dashboardType' => 'First Digits',
                ]);

                // Last Digits Dashboard
                $dashboards[] = DigitLotteryDashboard::create([
                    'price' => $validated['price'],
                    'date' => $startDate->toDateString(),
                    'draw' => $draw,
                    'draw_number' => $currentDrawNumber,
                    'winning_numbers' => json_encode($winningNumbers),
                    'lottery_id' => $validated['lottery_id'],
                    'dashboard' => $validated['dashboard'],
                    'status' => 'active',
                    'dashboardType' => 'Last Digits',
                ]);


                $draw++;
                $drawNumber++;
                $createdDays++;
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



    private function digitgenerateWinningNumbers()
    {
        $numbers = [];
        for ($i = 0; $i <= 9; $i++) {
            $numbers[] = (string) $i; // store as string "0" to "9"
        }
        return $numbers;
    }
}
