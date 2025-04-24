<?php
namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewConversationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $userName;
    public $conversationMessage;

    public function __construct(Message $message)
    {
        $this->conversationMessage = $message;
        $this->userName = $message->user->name ?? 'Unknown User'; 
    }

    public function build()
    {
        return $this->subject('New Conversation Started')
                    ->view('mail.new_conversation')
                    ->with([
                        'conversationMessage' => $this->conversationMessage,
                        'userName' => $this->userName,
                    ]);
    }
}
