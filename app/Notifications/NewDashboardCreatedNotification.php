<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewDashboardCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lotteryId;
    protected $dashboardName;
    protected $drawNumber;
    protected $date;

    public function __construct($lotteryId, $dashboardName, $drawNumber, $date)
    {
        $this->lotteryId = $lotteryId;
        $this->dashboardName = $dashboardName;
        $this->drawNumber = $drawNumber;
        $this->date = $date;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        Log::info('Attempting to send new dashboard email notification', [
            'recipient' => $notifiable->email,
            'lottery_id' => $this->lotteryId,
            'dashboard' => $this->dashboardName,
            'draw_number' => $this->drawNumber,
        ]);

        try {
            $mail = (new MailMessage)
                ->subject('New Dashboard Created')
                ->line("A new dashboard has been created for your lottery.")
                ->line("Lottery ID: {$this->lotteryId}")
                ->line("Dashboard: {$this->dashboardName}")
                ->line("Draw Number: {$this->drawNumber}")
                ->line("Date: {$this->date}")
                ->action('View Dashboard', url('/dashboard'))
                ->line('Thank you for using our platform!');

            Log::info('New dashboard email notification prepared successfully', [
                'recipient' => $notifiable->email,
                'subject' => 'New Dashboard Created'
            ]);

            return $mail;

        } catch (\Exception $e) {
            Log::error('Failed to prepare new dashboard email notification', [
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
            'lottery_id' => $this->lotteryId,
            'dashboard_name' => $this->dashboardName,
            'draw_number' => $this->drawNumber,
            'date' => $this->date,
            'message' => "A new dashboard has been created for lottery {$this->lotteryId} ({$this->dashboardName}) with draw number {$this->drawNumber}.",
        ];
    }
}