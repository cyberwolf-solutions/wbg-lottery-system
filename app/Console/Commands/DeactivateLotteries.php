<?php

namespace App\Console\Commands;

use App\Models\LotteryDashboards;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeactivateLotteries extends Command
{
    protected $signature = 'lottery:deactivate';
    protected $description = 'Deactivate expired lotteries';

    public function handle()
    {
        $now = Carbon::now();
        $expiredLotteriesCount = LotteryDashboards::where('date', '<', $now->toDateString())
            ->whereTime('date', '<', '20:00:00') // Ensure it's past 8 PM
            ->where('status', 'active') // Check status instead of is_active
            ->update(['status' => 'deactive']); // Update to "deactive"

        // Display how many rows were updated
        $this->info("$expiredLotteriesCount expired lotteries deactivated.");
    }
}
