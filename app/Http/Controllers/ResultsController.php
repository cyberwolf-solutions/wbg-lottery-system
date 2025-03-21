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
                'draw_number' => 'required'
            ]);

            // Fetch all dashboards with the same draw number
            $dashboards = LotteryDashboards::where('draw_number', $validatedData['draw_number'])->get();

            foreach ($dashboards as $dashboard) {
                // Check if the result already exists for this dashboard
                $existingResult = Results::where('lottery_id', $validatedData['lottery_id'])
                    ->where('dashboard_id', $dashboard->id)
                    ->where('winning_number', $validatedData['winning_number'])
                    ->first();

                if (!$existingResult) {
                    // Add result to this dashboard
                    Results::create([
                        'lottery_id' => $validatedData['lottery_id'],
                        'dashboard_id' => $dashboard->id,
                        'winning_number' => (string) $validatedData['winning_number'],
                    ]);
                }

                // Fetch picked numbers for the current dashboard and lottery
                $pickedNumbers = PickedNumber::where('lottery_id', $validatedData['lottery_id'])
                    ->where('lottery_dashboard_id', $dashboard->id)
                    ->where('picked_number', $validatedData['winning_number'])
                    ->get();

                $winningPrice = $dashboard->price * 70;

                // Store winners and update wallets
                foreach ($pickedNumbers as $pickedNumber) {
                    Winner::create([
                        'lottery_id' => $pickedNumber->lottery_id,
                        'lottery_dashboard_id' => $pickedNumber->lottery_dashboard_id,
                        'user_id' => $pickedNumber->user_id,
                        'winning_number' => $pickedNumber->picked_number,
                        'price' => $winningPrice
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
                        'picked_number' => $pickedNumber->picked_number
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

            Log::info('Results and winners added successfully', [
                'lottery_id' => $validatedData['lottery_id'],
                'winning_number' => $validatedData['winning_number']
            ]);

            return response()->json(['success' => true, 'message' => 'Results and winners added successfully']);
        } catch (Exception $e) {
            Log::error('Error storing lottery result', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['success' => false, 'message' => 'An error occurred while adding the result'], 500);
        }
    }
}
