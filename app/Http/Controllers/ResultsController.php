<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\Winner;
use App\Models\Results;
use App\Models\Lotteries;
use App\Models\Transaction;
use App\Models\PickedNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Lottery;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Notifications\WinnerNotification;

class ResultsController extends Controller
{
    //

    public function index()
    {
        $lotteries = Lotteries::with('dashboards')->get();

        $results = Results::with('lottery', 'dashboard')
            ->orderBy('lottery_id')
            ->get()
            ->groupBy('lottery_id');

        foreach ($lotteries as $lottery) {
            // Keep track of seen draw numbers
            $seenDrawNumbers = [];

            foreach ($lottery->dashboards as $key => $dashboard) {
                if (in_array($dashboard->draw_number, $seenDrawNumbers)) {
                    // Remove duplicate dashboards with the same draw_number
                    unset($lottery->dashboards[$key]);
                } else {
                    // Mark draw_number as seen
                    $seenDrawNumbers[] = $dashboard->draw_number;
                }

                // Check if a result already exists
                $lotteryResults = $results->get($lottery->id);
                if ($lotteryResults) {
                    $existingResult = $lotteryResults->where('dashboard_id', $dashboard->id)->first();
                    if ($existingResult) {
                        unset($lottery->dashboards[$key]);
                    }
                }
            }
        }

        return Inertia::render('AdminDashboard/results', [
            'results' => $results,
            'lotteries' => $lotteries
        ]);
    }






    public function store(Request $request)
    {
        try {
            Log::info('Storing lottery result', $request->all());

            // Validate incoming data
            $validatedData = $request->validate([
                'lottery_id' => 'required|exists:lotteries,id',
                'dashboard_id' => 'required|exists:lottery_dashboards,id',
                'winning_number' => 'required',
                'lwinning_number' => 'required',
                'draw_number' => 'required',
                'dashboard_name'=>'required'
            ]);

            Log::info('Data', $request->all());

            // Fetch the two dashboards (FirstDigit and LastDigit) for the given draw number
            $dashboards = LotteryDashboards::where('draw_number', $validatedData['draw_number'])
            ->whereIn('dashboardType', ['First Digits', 'Last Digits'])
            ->where('dashboard', $validatedData['dashboard_name']) // Check by dashboard_name as well
            ->get();
    

            if ($dashboards->count() != 2) {
                throw new \Exception('Expected two dashboards (FirstDigit and LastDigit) for the given draw number.');
            }

            // Separate the dashboards
            $firstDigitDashboard = $dashboards->firstWhere('dashboardType', 'First Digits');
            $lastDigitDashboard = $dashboards->firstWhere('dashboardType', 'Last Digits');

            if (!$firstDigitDashboard || !$lastDigitDashboard) {
                throw new \Exception('Both FirstDigit and LastDigit dashboards are required.');
            }

            // Add results to the FirstDigit dashboard
            $this->addResultToDashboard($validatedData, $firstDigitDashboard, $validatedData['winning_number']);

            // Add results to the LastDigit dashboard
            $this->addResultToDashboard($validatedData, $lastDigitDashboard, $validatedData['lwinning_number']);

            Log::info('Results added successfully', [
                'lottery_id' => $validatedData['lottery_id'],
                'winning_number' => $validatedData['winning_number'],
                'lwinning_number' => $validatedData['lwinning_number'],
            ]);

            return response()->json(['success' => true, 'message' => 'Results added successfully']);
        } catch (\Exception $e) {
            Log::error('Error storing lottery result', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper method to add results to a specific dashboard and handle winners/transactions.
     */
    private function addResultToDashboard($validatedData, $dashboard, $winningNumber)
    {
        // Check if the result already exists for this dashboard
        $existingResult = Results::where('lottery_id', $validatedData['lottery_id'])
            ->where('dashboard_id', $dashboard->id)
            ->where('winning_number', $winningNumber)
            ->first();

        if (!$existingResult) {
            // Add result to this dashboard
            Results::create([
                'lottery_id' => $validatedData['lottery_id'],
                'dashboard_id' => $dashboard->id,
                'winning_number' => (string) $winningNumber,
            ]);
        }

        // Fetch picked numbers for the current dashboard and lottery
        $pickedNumbers = PickedNumber::where('lottery_id', $validatedData['lottery_id'])
            ->where('lottery_dashboard_id', $dashboard->id)
            ->where('picked_number', $winningNumber)
            ->get();

        $winningPrice = $dashboard->price * 70;

        // Store winners and update wallets
        foreach ($pickedNumbers as $pickedNumber) {
            Winner::create([
                'lottery_id' => $pickedNumber->lottery_id,
                'lottery_dashboard_id' => $pickedNumber->lottery_dashboard_id,
                'user_id' => $pickedNumber->user_id,
                'winning_number' => $pickedNumber->picked_number,
                'price' => $winningPrice,
            ]);

            $wallet = Wallet::firstOrCreate(['user_id' => $pickedNumber->user_id]);

            // Add winning amount to the wallet
            $wallet->increment('available_balance', $winningPrice);

            // Save the transaction for the winning amount
            Transaction::create([
                'wallet_id' => $wallet->id,
                'amount' => $winningPrice,
                'type' => 'Winning',
                'lottery_id' => $validatedData['lottery_id'],
                'lottery_dashboard_id' => $dashboard->id,
                'transaction_date' => Carbon::now(),
                'picked_number' => $pickedNumber->picked_number,
            ]);

            $user = User::find($pickedNumber->user_id);
            $lottery = Lotteries::find($validatedData['lottery_id']);
            Log::info('User and lottery fetched', ['user' => $user, 'lottery' => $lottery]);

            if ($user) {
                $user->notify(new WinnerNotification(
                    $lottery->name,
                    $pickedNumber->picked_number,
                    $dashboard->draw_number,
                    $winningPrice
                ));
                Log::info('Notification sent to user', ['user_id' => $user->id]);
            } else {
                Log::warning('User not found', ['user_id' => $pickedNumber->user_id]);
            }
        }
    }
}
