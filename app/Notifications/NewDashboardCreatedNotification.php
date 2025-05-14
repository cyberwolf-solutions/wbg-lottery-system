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

    protected $dashboards;

    public function __construct(array $dashboards)
    {
        $this->dashboards = $dashboards;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        Log::info('Attempting to send new dashboards email notification', [
            'recipient' => $notifiable->email,
            'dashboard_count' => count($this->dashboards),
        ]);

        try {
            $mail = (new MailMessage)
                ->subject('New Dashboards Created');

            if (count($this->dashboards) > 1) {
                $mail->line("New dashboards have been created for your lotteries:");
            } else {
                $mail->line("A new dashboard has been created for your lottery:");
            }

            foreach ($this->dashboards as $dashboard) {
                $mail->line("-------------------------------")
                     ->line("Lottery ID: {$dashboard['lottery_id']}")
                     ->line("Dashboard: {$dashboard['dashboard_name']}")
                     ->line("Draw Number: {$dashboard['draw_number']}")
                     ->line("Date: {$dashboard['date']}");
            }

            $mail->action('View Dashboards', url('/dashboard'))
                ->line('Thank you for using our platform!');

            Log::info('New dashboards email notification prepared successfully', [
                'recipient' => $notifiable->email,
                'subject' => 'New Dashboards Created'
            ]);

            return $mail;

        } catch (\Exception $e) {
            Log::error('Failed to prepare new dashboards email notification', [
                'error' => $e->getMessage(),
                'recipient' => $notifiable->email,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function toArray($notifiable)
    {
        $message = count($this->dashboards) > 1 ? 
            "New dashboards have been created for your lotteries." : 
            "A new dashboard has been created for your lottery.";

        return [
            'dashboards' => $this->dashboards,
            'message' => $message,
        ];
    }
}