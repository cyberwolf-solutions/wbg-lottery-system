<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
        Log::info('Sending WinnerNotification via channels', [
            'channels' => ['mail', 'database'],
            'recipient' => $notifiable->email
        ]);
        return ['mail', 'database'];
    }
    public function toMail($notifiable)
    {
        Log::info('Attempting to send winner email notification', [
            'recipient' => $notifiable->email,
            'lottery' => $this->lotteryName,
            'picked_number' => $this->pickedNumber,
            'price' => $this->price,
        ]);
    
        try {
            $mail = (new MailMessage)
                ->subject('Congratulations! You Won!')
                ->line("Congratulations! You have won $ {$this->price} from {$this->lotteryName} lottery.")
                ->line("Winning Number: {$this->pickedNumber}")
                ->line("Draw Number: {$this->drawNumber}")
                ->action('Claim Your Prize', url('/dashboard'))
                ->line('Thank you for participating!');
    
            Log::info('Winner email notification prepared successfully', [
                'recipient' => $notifiable->email,
                'subject' => 'Congratulations! You Won!'
            ]);
    
            return $mail;
    
        } catch (\Exception $e) {
            Log::error('Failed to prepare winner email notification', [
                'error' => $e->getMessage(),
                'recipient' => $notifiable->email,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
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
