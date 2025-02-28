<?php

namespace App\Jobs;

use App\Events\NumberPicked;
use App\Models\PickedNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
class PickNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $number;
    protected $lotteryDashboardId;
    protected $userId; // Add this

    /**
     * Create a new job instance.
     *
     * @param string $number
     * @param int $lotteryDashboardId
     * @param int|null $userId
     */
    public function __construct($number, $lotteryDashboardId, $userId = null)
    {
        $this->number = $number;
        $this->lotteryDashboardId = $lotteryDashboardId;
        $this->userId = $userId; // Assign user ID

        Log::info("PickNumberJob dispatched: Number = {$number}, Lottery ID = {$lotteryDashboardId}, User ID = {$userId}");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("PickNumberJob started processing: Number = {$this->number}, Lottery ID = {$this->lotteryDashboardId}, User ID = {$this->userId}");

        $exists = PickedNumber::where('lottery_dashboard_id', $this->lotteryDashboardId)
            ->where('picked_number', $this->number)
            ->exists();

        if ($exists) {
            return;
        }

        // Assign the number using the passed user ID
        $pickedNumber = PickedNumber::create([
            'user_id' => $this->userId, // Use the stored user ID
            'picked_number' => $this->number,
            'lottery_dashboard_id' => $this->lotteryDashboardId,
        ]);

        broadcast(new NumberPicked($pickedNumber))->toOthers();

        Log::info("PickNumberJob completed.");
    }
}

