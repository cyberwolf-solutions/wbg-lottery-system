<?php

namespace App\Http\Controllers;


use App\Models\User;
use Inertia\Inertia;
use App\Models\Message;
use App\Events\NewMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewConversationMail;


class MessageController extends Controller
{
    public function index()
    {

        if (Auth::guard('admin')->check()) {
            $messages = Message::with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('user_id');

            $admin = Auth::guard('admin')->user();
            Log::info('Admin user retrieved', ['admin' => $admin]);

            return Inertia::render('AdminDashboard/chat', [
                'groupedMessages' => $messages,
                'users' => User::whereIn('id', $messages->keys())->get(),
                'admin' => $admin,
            ]);
        }
    }
    public function userindex()
    {
        Log::info('MessageController index method called', ['user_id' => Auth::id()]);
        $userId = Auth::id();
        $messages = Message::with(['user', 'admin'])
            ->where('user_id', $userId)
            ->orWhere(function ($query) use ($userId) {
                $query->where('admin_id', 1)
                    ->where('user_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        Log::info('Messages:', ['messages' => $messages]);
        return response()->json(['messages' => $messages]);
    }

    public function store(Request $request)
    {
        $data = [
            'message' => $request->message,
            'user_id' => Auth::id(),
            'admin_id' => 1,
            'is_from_user' => true,
        ];



        $message = Message::create($data)->load('user');

       
        $isFirstMessage = Message::where('user_id', Auth::id())->exists();

        
        Log::info('isFirstMessage:', ['isFirstMessage' => $isFirstMessage]);
        // dd($isFirstMessage);

        if (!$isFirstMessage) {
            try {

                Mail::to('info@winboardgame.com')->send(new NewConversationMail($message));


                Log::info('New conversation email sent successfully.', ['message' => $message->id]);
            } catch (\Exception $e) {

                Log::error('Failed to send new conversation email.', [
                    'error' => $e->getMessage(),
                    'message_id' => $message->id
                ]);
            }
        }



        broadcast(new NewMessage($message));

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function adminStore(Request $request)
    {
        $data = [
            'message' => $request->message,
            'user_id' => $request->user_id,
            'admin_id' => Auth::guard('admin')->id(),
            'is_from_user' => false,
        ];

        $message = Message::create($data);
        $message->load('user', 'admin');

        broadcast(new NewMessage($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
