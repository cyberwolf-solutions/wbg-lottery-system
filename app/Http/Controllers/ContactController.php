<?php


namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    // In ContactController
    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        $data = [
            'name' => $validated['full_name'],
            'contact' => $validated['contact_number'],
            'email' => $validated['email'],
            'message' => $validated['message'],
        ];
 
        Log::info($data);

        // Send using the Mailable
        Mail::to('info@winboardgame.com')
            ->send(new ContactMail($data));

        return response()->json(['message' => 'Email sent successfully!']);
    }
}
