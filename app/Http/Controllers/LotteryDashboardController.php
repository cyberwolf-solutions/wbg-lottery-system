<?php

namespace App\Http\Controllers;

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
            ]);

            // Generate the winning numbers (00, 01, 02, ..., 99)
            $winningNumbers = $this->generateWinningNumbers();

            // Create a new Lottery Dashboard record in the database
            $dashboard = LotteryDashboards::create([
                'price' => $validated['price'],
                'date' => $validated['date'],
                'draw' => $validated['draw'],
                'draw_number' => str_pad($validated['drawNumber'], 3, '0', STR_PAD_LEFT),
                'winning_numbers' => json_encode($winningNumbers),
                'lottery_id' => $validated['lottery_id'],
                'dashboard' => $validated['dashboard']
            ]);

            // Return the response
            return response()->json([
                'message' => 'Dashboard created successfully!',
                'dashboard' => $dashboard,
            ], 201);
        } catch (\Exception $e) {
            // Log the error details to the log file
            Log::error('Error creating Lottery Dashboard:', [
                'error_message' => $e->getMessage(),
                'error_stack' => $e->getTraceAsString(),
                'request_data' => $request->all(), // Optionally log the request data
            ]);

            // Return a response with the error
            return response()->json([
                'message' => 'There was an error creating the dashboard. Please try again later.',
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


    public function pickNumber(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required',
            'lottery_dashboard_id' => 'required|exists:lottery_dashboards,id',
        ]);


        Log::info("Dispatching PickNumberJob: Number = {$validated['number']}, Lottery ID = {$validated['lottery_dashboard_id']}, User ID = " . Auth::id());
       
        
        PickNumberJob::dispatch($validated['number'], $validated['lottery_dashboard_id'] ,Auth::id());



        

        return response()->json(['message' => 'Number picked successfully']);
    }
    
}
