<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Log;

class DeactivateLotteries extends Command
{
    protected $signature = 'lottery:deactivate';
    protected $description = 'Deactivate expired lotteries';

    public function handle()
    {
        Log::info('Starting lottery deactivation command');
        $now = Carbon::now('Asia/Colombo');
        Log::info('Current time: ' . $now);


        if ($now->format('H:i') == '20:00') {
            $expiredLotteries = LotteryDashboards::whereDate('date', '<=', $now->toDateString())
                ->where('status', 'active')
                ->get();


            Log::info('Found ' . count($expiredLotteries) . ' lotteries to deactivate');

            $expiredLotteriesCount = $expiredLotteries->count();
            LotteryDashboards::whereIn('id', $expiredLotteries->pluck('id'))
                ->update(['status' => 'deactive']);

            Log::info("$expiredLotteriesCount expired lotteries deactivated.");
            $this->info("$expiredLotteriesCount expired lotteries deactivated.");

        } else {

            Log::info('command skipped .Current time is not 8 p.m');
            $this->info('Skipped: Is is not 8 PM Sri lanka time');
        }
    }
}
