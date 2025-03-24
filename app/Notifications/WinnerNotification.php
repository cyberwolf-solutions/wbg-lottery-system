<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class WinnerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lotteryName;
    protected $pickedNumber;
    protected $drawNumber;
    protected $price;

    public function __construct($lotteryName, $pickedNumber, $drawNumber, $price)
    {
        $this->lotteryName = $lotteryName;
        $this->pickedNumber = $pickedNumber;
        $this->drawNumber = $drawNumber;
        $this->price = $price;

        Log::info('WinnerNotification created', [
            'lotteryName' => $lotteryName,
            'pickedNumber' => $pickedNumber,
            'drawNumber' => $drawNumber,
            'price' => $price,
        ]);
    }

    public function via($notifiable)
    {
        Log::info('Sending WinnerNotification via database', ['notifiable' => $notifiable]);
        return ['database'];
    }

    public function toArray($notifiable)
    {
        Log::info('Preparing WinnerNotification data', ['notifiable' => $notifiable]);
        return [
            'lottery_name' => $this->lotteryName,
            'picked_number' => $this->pickedNumber,
            'draw_number' => $this->drawNumber,
            'price' => $this->price,
            'message' => "Congratulations! You have won $ {$this->price} from  {$this->lotteryName} lottery with number {$this->pickedNumber} for draw {$this->drawNumber}.",
        ];
    }
}