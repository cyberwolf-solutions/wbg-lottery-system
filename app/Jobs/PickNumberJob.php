<?php

namespace App\Jobs;

use App\Models\User;
use App\Events\NumberPicked;
use App\Models\LotteryDashboards;
use App\Models\PickedNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PickNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $number;
    protected $lotteryDashboardId;
    protected $userId;

    protected $lottery_id;

    public function __construct($number, $lotteryDashboardId, $lottery_id, $userId = null)
    {
        $this->number = $number;
        $this->lotteryDashboardId = $lotteryDashboardId;
        $this->lottery_id = $lottery_id;
        $this->userId = $userId;



        Log::info("PickNumberJob dispatched: Number = {$number}, Lottery Dashboard ID = {$lotteryDashboardId}, Lottery ID = {$lottery_id} ,User ID = {$userId}");
    }

    public function handle()
    {
        // Log::info("PickNumberJob started processing: Number = {$this->number}, Lottery Dashboard ID = {$this->lotteryDashboardId}, Lottery ID = {$this->lottery_id},User ID = {$this->userId}");

        Log::info("Number: {$this->number}");
        Log::info("Lottery Dashboard ID: {$this->lotteryDashboardId}");
        Log::info("Lottery ID: {$this->lottery_id}");
        Log::info("User ID: {$this->userId}");


        // Find the allocated number and update its status to 'picked'
        $pickedNumber = PickedNumber::where('lottery_dashboard_id', $this->lotteryDashboardId)
            ->where('lottery_id', $this->lottery_id)
            ->where('picked_number', $this->number)
            ->where('status', 'allocated')
            ->first();

        Log::info("Attempting to update status for number: {$this->number}");

        if ($pickedNumber) {
            Log::info("Allocated number found: {$this->number}");
            $pickedNumber->update(['status' => 'picked']);
            Log::info("Number {$this->number} status updated to 'picked'.");
        } else {
            Log::warning("Allocated number not found for updating status: {$this->number}");
        }


        // Ensure wallet balance is updated
        // $user = User::find($this->userId);
        // if ($user && $user->wallet) {
        //     $lotteryDashboard = LotteryDashboards::find($this->lotteryDashboardId);
        //     if ($lotteryDashboard) {
        //         $user->wallet->decrement('available_balance', $lotteryDashboard->price);
        //     }
        // }

        // Broadcast the update
        broadcast(new NumberPicked($pickedNumber))->toOthers();

        Log::info("PickNumberJob completed.");
    }
}
