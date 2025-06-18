<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Message;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    //
    public function index()
    {
        $adminId = Auth::guard('admin')->id();

        // Get unread messages
        $messages = Message::with('user')
            // ->where('admin_id', $adminId)
            // ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function ($message) {
                return [
                    'id' => 'msg_' . $message->id,
                    'type' => 'message',
                    'content' => $message->message,
                    'user' => $message->user,
                    'created_at' => $message->created_at,
                    // 'read_at' => $message->read_at
                ];
            });

        // Get pending withdrawals
        $withdrawals = Withdrawal::with('wallet.user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function ($withdrawal) {
                return [
                    'id' => 'wd_' . $withdrawal->id,
                    'type' => 'withdrawal',
                    'amount' => $withdrawal->amount,
                    'wallet' => $withdrawal->wallet,
                    'user' => $withdrawal->wallet->user,
                    'created_at' => $withdrawal->created_at,
                    'status' => $withdrawal->status
                ];
            });

        // Get pending deposits
        $deposits = Deposit::with('wallet.user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function ($deposit) {
                return [
                    'id' => 'dp_' . $deposit->id,
                    'type' => 'deposit',
                    'amount' => $deposit->amount,
                    'wallet' => $deposit->wallet,
                    'user' => $deposit->wallet->user,
                    'created_at' => $deposit->created_at,
                    'status' => $deposit->status
                ];
            });

            Log::info('deposit', $deposits->toArray());

        // Combine all notifications and sort by creation date
        $notifications = $messages->concat($withdrawals)->concat($deposits)
            ->sortByDesc('created_at')
            ->take(20)
            ->values();

        return response()->json($notifications);
    }


    public function markAsRead()
    {
        Message::where('admin_id', Auth::guard('admin')->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
