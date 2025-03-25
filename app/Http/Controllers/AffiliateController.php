<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AffiliateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $affiliates = Affiliate::where('user_id', $user->id)
            ->with('affiliateUser')
            ->get();

        $totalEarnings = $affiliates->sum('price');
        $activeReferrals = $affiliates->count();

        $recentReferrals = User::where('affiliate_link', $user->user_affiliate_link)
            ->latest()
            ->take(3)
            ->get();

        // Group earnings by month
        $earningsByMonth = $affiliates->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('F'); // Get full month name (January, February, etc.)
        })->map(function ($month) {
            return $month->sum('price'); 
        });

        // Fill in missing months with zero earnings
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        foreach ($months as $month) {
            if (!isset($earningsByMonth[$month])) {
                $earningsByMonth[$month] = 0; // Set missing months to 0
            }
        }

        // Sort the array to ensure months are in order
        $earningsByMonth = $earningsByMonth->sortKeys(); // Sort by month names

        $userlink = $user->user_affiliate_link;

        return Inertia::render('User/Affiliate', [
            'status' => session('status'),
            'totalEarnings' => $totalEarnings,
            'activeReferrals' => $activeReferrals,
            'earningsByMonth' => $earningsByMonth,
            'recentReferrals' => $recentReferrals,
            'userlink' => $userlink,
        ]);
    }
}
