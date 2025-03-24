<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lotteries;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class LotteryListController extends Controller
{
    //

    public function index()
    {

        $lottery = Lotteries::all();

        return Inertia::render('AdminDashboard/LotteryList', [
            'lotteries' => $lottery
        ]);
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:256|unique:lotteries,name', // Add unique validation here
            'description' => 'required|string',
            'image' => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            'color' => 'required|String'
        ]);

        $image = $request->file('image');

        $imageName = time() . '_' . $image->getClientOriginalName();

        $destinationPath = public_path('images/lotteryimages');

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true);
        }

        $image->move($destinationPath, $imageName);

        $imagePath = 'assets/images/lotteryimages/' . $imageName;

        $adminId = auth('sanctum')->id();
        Log::debug($adminId);

        // Create a new lottery record
        $lottery = Lotteries::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'color' => $validated['color'],
        ]);

        return response()->json($lottery, 201);
    }

    public function update(Request $request, $id)
    {
        
        $lottery = Lotteries::find($id);

        if (!$lottery) {
            return response()->json(['message' => 'Lottery not found'], 404);
        }

        
        $validated = $request->validate([
            'name' => 'required|string|max:256|unique:lotteries,name,' . $lottery->id, 
            'description' => 'required|string',
            'image' => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            'color'=> 'required|String'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('images/lotteryimages');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true);
            }

            $image->move($destinationPath, $imageName);
            $imagePath = 'assets/images/lotteryimages/' . $imageName;

            $lottery->image = $imagePath;
        }

        $lottery->name = $validated['name'];
        $lottery->description = $validated['description'];
        $lottery->color= $validated['color'];
        $lottery->save();

        return response()->json($lottery, 200);
    }

    public function destroy($id)
{
    $lottery = Lotteries::find($id);

    if (!$lottery) {
        return response()->json(['message' => 'Lottery not found'], 404);
    }

    // Perform soft delete
    $lottery->delete();

    return response()->json(['message' => 'Lottery deleted successfully'], 200);
}

}
