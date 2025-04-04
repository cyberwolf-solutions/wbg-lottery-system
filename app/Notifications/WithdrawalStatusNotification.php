<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WithdrawalStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $status;
    protected $amount;
    protected $declineReason;

    public function __construct($status, $amount, $declineReason = null)
    {
        $this->status = $status;
        $this->amount = $amount;
        $this->declineReason = $declineReason;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject('Withdrawal Request ' . ucfirst($this->status))
            ->line("Your withdrawal request of {$this->amount} has been {$this->status}.");

        if ($this->status === 'declined' && $this->declineReason) {
            $mailMessage->line("Reason: {$this->declineReason}");
        }

        $mailMessage->action('View Your Wallet', url('/dashboard/wallet'))
                   ->line('Thank you for using our service!');

        return $mailMessage;
    }

    public function toArray($notifiable)
    {
        $notificationData = [
            'amount' => $this->amount,
            'status' => $this->status,
            'message' => "Your withdrawal request of {$this->amount} has been {$this->status}.",
        ];

        if ($this->status === 'declined') {
            $notificationData['decline_reason'] = $this->declineReason;
            $notificationData['message'] .= " Reason: {$this->declineReason}";
        }

        return $notificationData;
    }
}