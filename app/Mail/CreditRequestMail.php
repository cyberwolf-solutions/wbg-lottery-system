<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreditRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $requestType;
    public $details;
    public $userName;

    public function __construct($requestType, $details, $userName)
    {
        $this->requestType = $requestType;
        $this->details = $details;
        $this->userName = $userName;
    }

    public function build()
    {
        try {
            return $this->subject("New {$this->requestType} Request")
                        ->view('mail.credit_request')
                        ->with([
                            'requestType' => $this->requestType,
                            'details' => $this->details,
                            'userName' => $this->userName,
                        ]);
        } catch (\Exception $e) {
            Log::error('Failed to build CreditRequestMail.', [
                'request_type' => $this->requestType,
                'error' => $e->getMessage(),
                'user_name' => $this->userName,
                'details' => $this->details,
            ]);

            throw $e; // Re-throw the exception to ensure the error is caught in the controller
        }
    }
}