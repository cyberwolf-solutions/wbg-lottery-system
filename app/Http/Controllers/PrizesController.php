<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Prizes;
use App\Models\Winner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrizesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $winnings = Winner::with([
            'lottery:id,name,image,color', // Only select necessary fields
            'lotteryDashboard:id,dashboard,price,date,draw,winning_numbers'
        ])->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        $totalWinnings = $winnings->sum('price');

        // dd($totalWinnings);
        // dd($winnings);

        return Inertia::render('User/Prize', [
            'status' => session('status'),
            'winnings' => $winnings,
            'totalWinnings' => $totalWinnings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Prizes $prizes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prizes $prizes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prizes $prizes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prizes $prizes)
    {
        //
    }
}
