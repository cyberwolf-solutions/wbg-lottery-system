<?php

namespace App\Events;

use App\Models\DigitPickedNumber;
use App\Models\PickedNumber;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DigitNumberPicked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pickedNumber;

    public function __construct(DigitPickedNumber $pickedNumber)
    {
        $this->pickedNumber = $pickedNumber;
    }

    public function broadcastOn()
    {
        return new Channel('lottery');
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->pickedNumber->user_id,
            'lottery_dashboard_id' => $this->pickedNumber->lottery_dashboard_id,
            'picked_number' => $this->pickedNumber->picked_number,
            'lottery_id' => $this->pickedNumber->lottery_id
        ];
    }
}
