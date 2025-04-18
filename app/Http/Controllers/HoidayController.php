<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HoidayController extends Controller
{
    public function index(){

        $holidays= Holiday::all();


        return Inertia::render('AdminDashboard/Holiday',[
            'holidays'=> $holidays
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required|string',
            'date'=>'required|date',
        ]);

        $exists = Holiday::where('date', $request->date)->first();

        if ($exists) {
            return response()->json([
                'message' => 'A holiday on this date already exists.'
            ], 409); 
        }

        $holiday =Holiday::create([
            'name'=> $request->name,
            'date'=> $request->date,
        ]);

        return response()->json($holiday, 201);
    }
}
