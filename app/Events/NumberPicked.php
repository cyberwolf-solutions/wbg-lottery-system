<?php

namespace App\Events;

use App\Models\PickedNumber;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NumberPicked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels , InteractsWithSockets;

    public $pickedNumber;

    /**
     * Create a new event instance.
     */
    public function __construct(PickedNumber $pickedNumber)
    {
        $this->pickedNumber = $pickedNumber;
    }

    public function broadcastOn(){
        return new Channel('lottery');
    }

    public function broadcastWith(){
        return [
            'user_id' => $this->pickedNumber->user_id,
            'lottery_dashboard_id' => $this->pickedNumber->lottery_dashboard_id,
            'picked_number'=> $this->pickedNumber->number,
        ];
    }



    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    
}
