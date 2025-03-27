<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{

    public function index()
    {
        return Inertia::render('AdminAuth/login', [
            'status' => session('status'),
        ]);
    }
   
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();

            return response()->json([
                'admin' => $admin,
            ], 200);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }




    public function logout()
    {
        try {

            Auth::guard('admin')->logout();


            Log::info('Admin logged out successfully.');


            return response()->json(['message' => 'Logged out successfully', 'redirect_url' => '/api/admin/login'], 200);
        } catch (\Exception $e) {

            Log::error('Logout failed: ' . $e->getMessage());


            return response()->json(['message' => 'Logout failed. Please try again.'], 500);
        }
    }

    public function checkAuth(Request $request)
    {
        $token = $request->cookie('admin_token');
        Log::info("Checking auth, received token: " . ($token ?? 'No token'));

        if (!$token) {
            return response()->json(['message' => 'Not authenticated'], 401);
        }

        $admin = Admin::where('remember_token', $token)->first();

        if ($admin) {
            return response()->json(['authenticated' => true]);
        } else {
            return response()->json(['message' => 'Invalid token'], 401);
        }
    }



    // Admin Logout
    // Admin Logout
    // public function logout(Request $request)
    // {

    //     $request->user()->tokens()->delete();


    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();


    //     // return response()->json([
    //     //     'message' => 'Admin logged out successfully'
    //     // ], 200);

    //     return redirect('/api/admin/login/index');
    // }


    // Get Admin Info
    // public function me(Request $request) {
    //     return response()->json($request->user());
    // }
}
