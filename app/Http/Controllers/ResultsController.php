<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\Winner;
use App\Models\Results;
use App\Models\Affiliate;
use App\Models\Lotteries;
use App\Models\Transaction;
use App\Models\PickedNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Lottery;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\WinnerNotification;
use App\Notifications\AffiliateCommissionNotification;

class ResultsController extends Controller
{
    //

    public function index()
    {
        $lotteries = Lotteries::with(['dashboards' => function ($query) {
            $query->where('date', '<', now()->format('Y-m-d'))
                ->where(function ($q) {
                    $q->where('status', 'deactive')
                        ->orWhere('status', 'closed');
                });
            // ->orderBy('date', 'desc');
        }])->get();

        $results = Results::with('lottery', 'dashboard')
            ->orderBy('lottery_id')
            ->get()
            ->groupBy('lottery_id');

        foreach ($lotteries as $lottery) {
            // Keep track of seen draw_number + dashboardType + dashboard
            $seenCombinations = [];

            foreach ($lottery->dashboards as $key => $dashboard) {
                // Create a unique key with draw_number + dashboardType + dashboard
                $uniqueKey = $dashboard->draw_number . '-' . $dashboard->dashboardType . '-' . $dashboard->dashboard;

                if (in_array($uniqueKey, $seenCombinations)) {
                    // Remove duplicate dashboards (same draw_number + dashboardType + dashboard)
                    unset($lottery->dashboards[$key]);
                    continue;
                } else {
                    // Mark this combination as seen
                    $seenCombinations[] = $uniqueKey;
                }

                // Check if a result already exists for this dashboard
                $lotteryResults = $results->get($lottery->id);
                if ($lotteryResults) {
                    $existingResult = $lotteryResults->where('dashboard_id', $dashboard->id)->first();
                    if ($existingResult) {
                        unset($lottery->dashboards[$key]);
                        continue;
                    }
                }
            }

            // Re-index the array after unsetting elements
            $lottery->dashboards = array_values($lottery->dashboards->toArray());
        }

        return Inertia::render('AdminDashboard/results', [
            'results' => $results,
            'lotteries' => $lotteries
        ]);
    }





    public function store(Request $request)
    {
        try {
            // Log::info('Storing lottery result', $request->all());

            // Validate incoming data
            $validatedData = $request->validate([
                'lottery_id' => 'required|exists:lotteries,id',
                'dashboard_id' => 'required|exists:lottery_dashboards,id',
                'winning_number' => 'required',
                'lwinning_number' => 'required',
                'draw_number' => 'required',
                'dashboard_name' => 'required'
            ]);

            // Log::info('Data', $request->all());

            // Fetch the two dashboards (FirstDigit and LastDigit) for the given draw number
            $dashboards = LotteryDashboards::where('draw_number', $validatedData['draw_number'])

                ->where('lottery_id', $validatedData['lottery_id'])
                ->whereIn('dashboardType', ['First Digits', 'Last Digits'])
                ->where('dashboard', $validatedData['dashboard_name'])
                ->where(function ($q) {
                    $q->where('status', 'deactive')
                        ->orWhere('status', 'closed');
                })
                ->get();


            Log::info('Data', ['dashboards' => $dashboards->toArray()]);
            // dd($dashboards);


            // if ($dashboards->count() != 2) {
            //     throw new \Exception('Expected two dashboards (FirstDigit and LastDigit) for the given draw number.');
            // }

            // Separate the dashboards
            $firstDigitDashboards = $dashboards->where('dashboardType', 'First Digits');
            $lastDigitDashboards = $dashboards->where('dashboardType', 'Last Digits');


            // if ($firstDigitDashboards->isEmpty() || $lastDigitDashboards->isEmpty()) {
            //     throw new \Exception('At least one FirstDigit and one LastDigit dashboard are required.');
            // }


            // Add result to all matching First Digits dashboards
            foreach ($firstDigitDashboards as $fdDashboard) {
                $this->addResultToDashboard($validatedData, $fdDashboard, $validatedData['winning_number']);
            }

            // Add result to all matching Last Digits dashboards
            foreach ($lastDigitDashboards as $ldDashboard) {
                $this->addResultToDashboard($validatedData, $ldDashboard, $validatedData['lwinning_number']);
            }

            // // Add results to the FirstDigit dashboard
            // $this->addResultToDashboard($validatedData, $firstDigitDashboard, $validatedData['winning_number']);

            // // Add results to the LastDigit dashboard
            // $this->addResultToDashboard($validatedData, $lastDigitDashboard, $validatedData['lwinning_number']);

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
        if ($pickedNumbers->isEmpty()) {
            
            Winner::create([
                'lottery_id' => $validatedData['lottery_id'],
                'lottery_dashboard_id' => $dashboard->id,
                'user_id' => 1,  
                'winning_number' => $winningNumber,
                'price' => $winningPrice,
            ]);
        } else {
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

                // Process affiliate commission (10% of the winner's prize)
                $affiliate = null;
                if ($user->affiliate_link) {
                    $affiliate = User::where('user_affiliate_link', $user->affiliate_link)->first();
                    Log::info('Affiliate found', ['affiliate' => $affiliate]);
                }
                Log::info('Data', ['affiliate' => $affiliate]);

                if ($affiliate) {
                    $affiliateCommission = $winningPrice * 0.10;
                    $affiliateWallet = Wallet::firstOrCreate(['user_id' => $affiliate->id]);

                    // Add commission to affiliate's wallet
                    $affiliateWallet->increment('available_balance', $affiliateCommission);

                    // Save the transaction for the affiliate commission
                    Transaction::create([
                        'wallet_id' => $affiliateWallet->id,
                        'amount' => $affiliateCommission,
                        'type' => 'Affiliate Commission',
                        'lottery_id' => $validatedData['lottery_id'],
                        'lottery_dashboard_id' => $dashboard->id,
                        'transaction_date' => Carbon::now(),
                    ]);

                    Affiliate::create([
                        'user_id' => $affiliate->id,
                        'affiliate_user_id' => $user->id,
                        'price' => $affiliateCommission,
                        'date' => Carbon::now(),
                    ]);

                    if ($user) {  // Added check for $user existence
                        $affiliate->notify(new AffiliateCommissionNotification(
                            $user->name,
                            $affiliateCommission,
                            $lottery->name
                        ));
                    }

                    Log::info('Affiliate commission added', [
                        'affiliate_user_id' => $affiliate->user_id,
                        'commission' => $affiliateCommission,
                    ]);
                }
            }
        }
    }
}
