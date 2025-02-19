<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
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
    // Admin Login
    // Admin Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            // Log the error
            Log::error('Login failed for email: ' . $request->email);

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Generate a token with remember me functionality
        $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

        if ($request->remember_me) {
            
            $cookie = cookie('admin_token', $token, 43200); 
            return response()->json([
                'admin' => $admin,
                'token' => $token
            ], 200)->cookie($cookie); 
        }

        // Default case without remember me
        return response()->json([
            'admin' => $admin,
            'token' => $token
        ], 200);
    }


    // Admin Logout
    // Admin Logout
    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();


        $request->session()->invalidate();
        $request->session()->regenerateToken();


        // return response()->json([
        //     'message' => 'Admin logged out successfully'
        // ], 200);

        return redirect('/api/admin/login/index');
    }


    // Get Admin Info
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
