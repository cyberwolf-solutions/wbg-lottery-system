<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LotteryRefundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lotteryName;
    protected $drawNumber;
    protected $amount;
    protected $pickedNumber;

    public function __construct($lotteryName, $drawNumber, $amount, $pickedNumber)
    {
        $this->lotteryName = $lotteryName;
        $this->drawNumber = $drawNumber;
        $this->amount = $amount;
        $this->pickedNumber = $pickedNumber;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Lottery Cancellation and Refund Notification')
            ->line("The {$this->lotteryName} lottery (Draw #{$this->drawNumber}) has been cancelled due to low participation.")
            ->line("Your picked number {$this->pickedNumber} money has been refunded.")
            ->line("Refund Amount: {$this->amount}")
            ->action('View Your Wallet', url('/dashboard/wallet'))
            ->line('Thank you for your participation!');
    }

    public function toArray($notifiable)
    {
        return [
            'lottery_name' => $this->lotteryName,
            'draw_number' => $this->drawNumber,
            'amount' => $this->amount,
            'picked_number' => $this->pickedNumber,
            'message' => "You've received a refund of {$this->amount} for {$this->lotteryName} lottery (Draw #{$this->drawNumber}) due to cancellation.",
        ];
    }
}