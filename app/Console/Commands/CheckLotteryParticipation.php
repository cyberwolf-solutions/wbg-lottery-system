<?php

namespace App\Console\Commands;

use Carbon\Carbon;
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

        $now = Carbon::now('Asia/Colombo');
        Log::info('Current time: ' . $now);

        $totalNumbers = 100;
        $threshold = 0.8;

        if ($now->format('H:i') == '19:59') {
            $dashboards = LotteryDashboards::where('status', 'active')
                ->whereDate('date', Carbon::today())
                ->get();

            Log::info('Active Dashboards for Today:', $dashboards->toArray());

            foreach ($dashboards as $dashboard) {
                $pickedCount = PickedNumber::where('lottery_dashboard_id', $dashboard->id)->count();
                $participationRate = $pickedCount / $totalNumbers;

                Log::info("Checking Lottery Dashboard ID {$dashboard->id}: Picked Count = {$pickedCount}, Participation Rate = {$participationRate}");

                if ($participationRate < $threshold) {
                    DB::transaction(function () use ($dashboard) {
                        // Cancel the lottery
                        $dashboard->update(['status' => 'cancelled']);
                        Log::info("Lottery Dashboard ID {$dashboard->id} was cancelled due to low participation.");

                        // Fetch all picked numbers and group by user
                        $pickedNumbers = PickedNumber::with('user')
                            ->where('lottery_dashboard_id', $dashboard->id)

                            ->where('status', 'picked')
                            ->get()
                            ->groupBy('user_id');


                        $delete = PickedNumber::where('lottery_dashboard_id', $dashboard->id)
                            ->where('status', 'allocated')
                            ->delete();

                        Log::info('Deleted allocated numbers: ' . $delete);

                        Log::info("Picked Numbers for  :", $pickedNumbers->toArray());





                        foreach ($pickedNumbers as $userId => $picks) {
                            $user = $picks->first()->user;
                            $wallet = Wallet::where('user_id', $userId)->first();

                            if ($wallet) {
                                $totalRefund = 0;
                                $refundDetails = [];

                                foreach ($picks as $pick) {
                                    $totalRefund += $dashboard->price;


                                    // Log each refund transaction
                                    $transaction = Transaction::create([
                                        'user_id' => $userId,
                                        'amount' => $dashboard->price,
                                        'type' => 'refund',
                                        'description' => 'Refund for cancelled lottery',
                                        'wallet_id' => $wallet->id,
                                        'transaction_date' => now(),
                                        'lottery_id' => $dashboard->lottery_id,
                                        'lottery_dashboard_id' => $dashboard->id,
                                        'picked_number' => $pick->number,
                                    ]);

                                    $refundDetails[] = [
                                        'lottery_name' => $dashboard->lottery->name,
                                        'draw_number' => $dashboard->draw_number,
                                        'picked_number' => $pick->number,
                                        'amount' => $dashboard->price
                                    ];

                                    Log::info("Refunded user {$userId} amount {$dashboard->price} for number {$pick->number}.");
                                }

                                // Update wallet with total refund
                                $wallet->increment('available_balance', $totalRefund);

                                // Send single notification with all refund details
                                if ($user) {
                                    // Log before sending notification
                                    Log::info("Preparing to send refund notification to user {$userId} with details:", [
                                        'total_refund' => $totalRefund,
                                        'refund_details' => $refundDetails
                                    ]);

                                    $user->notify(new LotteryRefundNotification($refundDetails, $totalRefund));
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
        } else {
            Log::info('command skipped. Current time is not 7:59 p.m');
            $this->info('Skipped: It is not 7:59 PM Sri Lanka time');
        }
    }
}