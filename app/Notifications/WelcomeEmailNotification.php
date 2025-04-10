<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class WelcomeEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Log before sending the email
        Log::info('Sending welcome email to: ' . $notifiable->email, [
            'user_id' => $notifiable->id,
            'time' => now()->toDateTimeString(),
        ]);

        $mail = (new MailMessage)
                    ->subject('Welcome to Win Board Game')
                    ->line('Thank you for registering with us!')
                    ->line('Your account has been successfully created.')
                    ->action('Visit Your Dashboard', url('/dashboard'))
                    ->line('If you have any questions, please contact our support team.');

        // Optionally log after creating the mail message
        Log::debug('Welcome email content prepared for: ' . $notifiable->email);

        return $mail;
    }

    /**
     * Called when the notification is sent.
     */
    public function sent($notifiable, $channel, $response = null)
    {
        Log::info('Welcome email sent successfully to: ' . $notifiable->email, [
            'user_id' => $notifiable->id,
            'channel' => $channel,
            'response' => $response,
            'time' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Called when the notification fails to send.
     */
    public function failed($notifiable, $channel, $exception)
    {
        Log::error('Failed to send welcome email to: ' . $notifiable->email, [
            'user_id' => $notifiable->id,
            'channel' => $channel,
            'error' => $exception->getMessage(),
            'time' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}