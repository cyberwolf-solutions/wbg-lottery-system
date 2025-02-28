<?php

namespace App\Jobs;

use App\Models\PickedNumber;
use App\Events\NumberPicked;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class PickNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $number;
    protected $lotteryDashboardId;

    /**
     * Create a new job instance.
     *
     * @param string $number
     * @param int $lotteryDashboardId
     */
    public function __construct($number, $lotteryDashboardId)
    {
        $this->number = $number;
        $this->lotteryDashboardId = $lotteryDashboardId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Check if the number is already picked
        $exists = PickedNumber::where('lottery_dashboard_id', $this->lotteryDashboardId)
            ->where('number', $this->number)
            ->exists();

        if ($exists) {
            // Handle if number already picked (maybe log it or notify the user)
            return;
        }

        // Assign the number to the logged user
        $pickedNumber = PickedNumber::create([
            'user_id' => Auth::id(),
            'picked_number' => $this->number,
            'lottery_dashboard_id' => $this->lotteryDashboardId,
        ]);

        // Broadcast the picked number
        broadcast(new NumberPicked($pickedNumber))->toOthers();
    }
}
