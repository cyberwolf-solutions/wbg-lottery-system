<?php


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('lottery', function ($user) {
    return $user != null;
});

Broadcast::channel('admin.{id}', function ($user, $id) {
    // $user will be the authenticated admin user
    Log::info('Admin user retrieved', ['admin' => $user]);
    return (int) $user->id === (int) $id;
});
Broadcast::channel('user.{id}', function ($user, $id) {
    Log::info('User channel auth attempt', [
        'authenticated_user' => $user->id,
        'requested_channel' => $id,
        'result' => (int) $user->id === (int) $id
    ]);
    
    return (int) $user->id === (int) $id;
});