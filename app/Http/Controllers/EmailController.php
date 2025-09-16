<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index()
    {
        // Fetch only necessary user fields
        $users = User::select('id', 'name', 'email')->get();
        return Inertia::render('AdminDashboard/email', ['users' => $users]);
    }


    public function sendEmail(Request $request)
    {
        // ✅ Validate request
        $request->validate([
            'recipients' => 'required', // should be JSON array of user IDs
            'header' => 'required|string',
            'body' => 'required|string',
            'file' => 'nullable|file|max:51200' // allow any file up to 50MB
        ]);

        $recipients = json_decode($request->recipients, true);

        // ✅ Handle file upload if provided
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('email_attachments', 'public');
            Log::info('Email attachment uploaded', ['path' => $filePath]);
        }

        // ✅ Get emails of selected users
        $emails = User::whereIn('id', $recipients)->pluck('email')->toArray();

        foreach ($emails as $email) {
            Log::info('Sending email to', ['email' => $email]);

            try {
                Mail::send([], [], function ($message) use ($request, $email, $filePath) {
                    $message->to($email)
                        ->subject($request->header)
                        ->html($request->body);

                    if ($filePath) {
                        $message->attach(storage_path('app/public/' . $filePath));
                    }
                });

                Log::info('Email sent successfully', ['email' => $email]);
            } catch (\Exception $e) {
                Log::error('Email failed to send', [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return response()->json(['message' => 'Emails processed. Check logs for details.']);
    }
}
