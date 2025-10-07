<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Winner;
use App\Models\Lotteries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WinnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lotteries = Lotteries::with(['dashboards.winners.user', 'digitDashboards.winners.user'])->get();

        $lotteries1 = Lotteries::with(['digitDashboards.winners.user'])->get();
        Log::info('Lotteries Data:', $lotteries1->toArray());

        return Inertia::render('User/Winners', [
            'lotteries' => $lotteries,
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
    public function show(Winner $winner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Winner $winner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Winner $winner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Winner $winner)
    {
        //
    }
}
