<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\PickedNumber;
use Illuminate\Console\Command;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\LotteryRefundNotification;

class CheckLotteryParticipation extends Command
{
    protected $signature = 'lottery:check-participation';
    protected $description = 'Check lottery dashboards and refund users if participation is below 80%';

    public function handle()
    {
        Log::info('Lottery check participation command started.');

        $totalNumbers = 100; // The total numbers available from 00 to 99
        $threshold = 0.8; // 80% threshold

        $dashboards = LotteryDashboards::where('status', 'active')->get();

        foreach ($dashboards as $dashboard) {
            $pickedCount = PickedNumber::where('lottery_dashboard_id', $dashboard->id)->count();
            $participationRate = $pickedCount / $totalNumbers;

            Log::info("Checking Lottery Dashboard ID {$dashboard->id}: Picked Count = {$pickedCount}, Participation Rate = {$participationRate}");

            if ($participationRate < $threshold) {
                DB::transaction(function () use ($dashboard) {
                    // Cancel the lottery
                    $dashboard->update(['status' => 'cancelled']);
                    Log::info("Lottery Dashboard ID {$dashboard->id} was cancelled due to low participation.");

                    // Fetch all picked numbers and refund users
                    $pickedNumbers = PickedNumber::with('user')->where('lottery_dashboard_id', $dashboard->id)->get();

                    foreach ($pickedNumbers as $pick) {
                        $wallet = Wallet::where('user_id', $pick->user_id)->first();
                        if ($wallet) {
                            $wallet->increment('available_balance', $dashboard->price);

                            // Log the refund transaction
                            $transaction = Transaction::create([
                                'user_id' => $pick->user_id,
                                'amount' => $dashboard->price,
                                'type' => 'refund',
                                'description' => 'Refund for cancelled lottery',
                                'wallet_id' => $wallet->id,
                                'transaction_date' => now(),
                                'lottery_id' => $dashboard->lottery_id,  
                                'lottery_dashboard_id' => $dashboard->id, 
                                'picked_number' => $pick->number, 
                            ]);

                            Log::info("Refunded user {$pick->user_id} amount {$dashboard->price}.");

                            // Send notification to user
                            if ($pick->user) {
                                $pick->user->notify(new LotteryRefundNotification(
                                    $dashboard->lottery->name,
                                    $dashboard->draw_number,
                                    $dashboard->price,
                                    $pick->number
                                ));
                                
                                Log::info("Notification sent to user {$pick->user_id} about refund.");
                            }
                        }
                    }

                    // Delete picked numbers since the lottery is canceled
                    PickedNumber::where('lottery_dashboard_id', $dashboard->id)->delete();
                    Log::info("Deleted picked numbers for Lottery Dashboard ID {$dashboard->id}.");
                });

                $this->info("Lottery Dashboard ID {$dashboard->id} was cancelled and users were refunded.");
            }
        }

        Log::info('Lottery check participation command completed.');
        return Command::SUCCESS;
    }
}