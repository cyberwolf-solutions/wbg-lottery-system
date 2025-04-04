<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminPlayersController extends Controller
{
    public function index()
    {
        // $users = User::with(['wallet', 'wallet.transactions' => function($query) {
        //     $query->orderBy('created_at', 'desc')->limit(5);
        // }])->where('active', true)->get();

        $users = User::with(['wallet', 'wallet.transactions' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(5);
        }])->get();


        // dd($users);
        return Inertia::render('AdminDashboard/Customers', [
            'users' => $users
        ]);
    }

    public function activate(User $user)
    {
        $user->update(['status' => 1]);

        return response()->json([
            'message' => 'User activated successfully'
        ]);
    }

    public function deactivate(User $user)
    {
        $user->update(['status' => 0]);

        return response()->json([
            'message' => 'User deactivated successfully'
        ]);
    }
}
