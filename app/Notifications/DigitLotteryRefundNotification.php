<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DigitLotteryRefundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $refundDetails;
    protected $totalRefund;

    public function __construct(array $refundDetails, $totalRefund)
    {
        $this->refundDetails = $refundDetails;
        $this->totalRefund = $totalRefund;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        // Build the email subject
        $subject = 'Lottery Cancellation and Refund Notification';

        // Start building the email content for logging
        $logContent = "Preparing to send refund email to user {$notifiable->id}:\n";
        $logContent .= "Subject: {$subject}\n";
        $logContent .= "Body:\n";
        $logContent .= "The following lotteries have been cancelled due to low participation and your money has been refunded:\n";

        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->line('The following lotteries have been cancelled due to low participation and your money has been refunded:');

        foreach ($this->refundDetails as $detail) {
            $lineContent = "Lottery: {$detail['lottery_name']}, Draw #: {$detail['draw_number']}, Picked Number: {$detail['picked_number']}, Refund Amount: {$detail['amount']}";
            $mailMessage->line($lineContent);
            $logContent .= $lineContent . "\n";
        }

        $totalLine = "Total Refund Amount: {$this->totalRefund}";
        $mailMessage->line($totalLine)
            ->action('View Your Wallet', url('/dashboard/wallet'))
            ->line('Thank you for your participation!');

        $logContent .= $totalLine . "\n";
        $logContent .= "Action Link: View Your Wallet (" . url('/dashboard/wallet') . ")\n";
        $logContent .= "Footer: Thank you for your participation!";

        // Log the complete email content
        Log::info($logContent);

        return $mailMessage;
    }

    public function toArray($notifiable)
    {
        $message = 'You\'ve received refunds for the following cancelled lotteries:';

        foreach ($this->refundDetails as $detail) {
            $message .= "\n- {$detail['lottery_name']} (Draw #{$detail['draw_number']}), Number: {$detail['picked_number']}, Amount: {$detail['amount']}";
        }

        $message .= "\nTotal Refund Amount: {$this->totalRefund}";

        return [
            'refund_details' => $this->refundDetails,
            'total_refund' => $this->totalRefund,
            'message' => $message,
        ];
    }
}
