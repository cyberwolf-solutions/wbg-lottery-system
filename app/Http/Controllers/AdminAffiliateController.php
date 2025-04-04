<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Affiliate;

class AdminAffiliateController extends Controller
{
    public function index()
    {
        $users = User::with(['wallet'])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'wallet' => $user->wallet ? ['available_balance' => $user->wallet->available_balance] : null,
                    'affiliates' => User::where('affiliate_link', $user->user_affiliate_link) // Find users referred by this user
                        ->get()
                        ->map(function ($affiliate) {
                            return [
                                'id' => $affiliate->id,
                                'name' => $affiliate->name,
                                'email' => $affiliate->email,
                                'earnings' => Affiliate::where('affiliate_user_id', $affiliate->id)->sum('price'), 
                                'date' => Affiliate::where('affiliate_user_id', $affiliate->id)->latest('date')->value('date'), 
                            ];
                        }),
                ];
            });
    
        return Inertia::render('AdminDashboard/Affiliate', [
            'users' => $users,
        ]);
    }
    
}
