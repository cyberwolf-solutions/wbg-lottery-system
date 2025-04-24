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
        $now = Carbon::now();
        Log::info('Current time: '.$now);
        
        $expiredLotteries = LotteryDashboards::where('date', '<', $now->toDateString())
            ->whereTime('date', '<', '13:00:00') 
            ->where('status', 'active')
            ->get();

            // $expiredLotteries = LotteryDashboards::where('date', '<', $now->toDateString())
            // ->whereTime('date', '<', '14:30:00') 
            // ->where('status', 'active')
            // ->get();
            
        Log::info('Found '.count($expiredLotteries).' lotteries to deactivate');
        
        $expiredLotteriesCount = $expiredLotteries->count();
        LotteryDashboards::whereIn('id', $expiredLotteries->pluck('id'))
            ->update(['status' => 'deactive']);
    
        Log::info("$expiredLotteriesCount expired lotteries deactivated.");
        $this->info("$expiredLotteriesCount expired lotteries deactivated.");
    }
}
