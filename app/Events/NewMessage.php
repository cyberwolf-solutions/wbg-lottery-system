<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.' . $this->message->user_id),
            new PrivateChannel('admin.' . $this->message->admin_id),
        ];
    }

    public function broadcastWith()
{
    return [
        'message' => [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'user_id' => $this->message->user_id,
            'admin_id' => $this->message->admin_id,
            'is_from_user' => $this->message->is_from_user,
            'created_at' => $this->message->created_at->toDateTimeString(),
            'isUnread' => false
        ]
    ];
}
}