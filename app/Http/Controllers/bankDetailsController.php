<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class bankDetailsController extends Controller
{
    //
    public function index()
    {
        // Fetch the bank details (assuming there's only one record)
        $bankDetails = Bank::first(); 
        // dd($bankDetails);

        // Pass bank details to the view
        return Inertia::render('AdminDashboard/bank', ['bankDetails' => $bankDetails]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'bank' => 'required|string|max:255',
           'number' => 'required|string|max:2000', 

        ]);

        // Find the bank details and update them
        $bankDetails = Bank::first();
        $bankDetails->update($request->all());

        return response()->json(['message' => 'Bank details updated successfully!']);
    }
}
