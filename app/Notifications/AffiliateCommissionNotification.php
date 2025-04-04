<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AffiliateCommissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $winnerName;
    protected $commissionAmount;
    protected $lotteryName;

    public function __construct($winnerName, $commissionAmount, $lotteryName)
    {
        $this->winnerName = $winnerName;
        $this->commissionAmount = $commissionAmount;
        $this->lotteryName = $lotteryName;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
{
    Log::info('Attempting to send affiliate commission email notification', [
        'recipient' => $notifiable->email,
        'winner' => $this->winnerName,
        'commission' => $this->commissionAmount,
        'lottery' => $this->lotteryName,
    ]);

    try {
        $mail = (new MailMessage)
            ->subject('Affiliate Commission Earned')
            ->line("Congratulations! You've earned a commission from your affiliate.")
            ->line("Winner: {$this->winnerName}")
            ->line("Lottery: {$this->lotteryName}")
            ->line("Commission Amount: $ {$this->commissionAmount}")
            ->action('View Your Dashboard', url('/dashboard'))
            ->line('Thank you for using our platform!');

        Log::info('Affiliate commission email notification prepared successfully', [
            'recipient' => $notifiable->email,
            'subject' => 'Affiliate Commission Earned'
        ]);

        return $mail;

    } catch (\Exception $e) {
        Log::error('Failed to prepare affiliate commission email notification', [
            'error' => $e->getMessage(),
            'recipient' => $notifiable->email,
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    }
}

    public function toArray($notifiable)
    {
        return [
            'winner_name' => $this->winnerName,
            'commission_amount' => $this->commissionAmount,
            'lottery_name' => $this->lotteryName,
            'message' => "You've earned $ {$this->commissionAmount} commission from {$this->winnerName}'s win in {$this->lotteryName} lottery.",
        ];
    }
}
