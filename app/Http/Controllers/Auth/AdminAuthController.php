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

class AdminAuthController extends Controller {

    public function index() {
        return Inertia::render('AdminAuth/login', [
            'status' => session('status'),
        ]);
    }
    // Admin Login
    // Admin Login
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $admin = Admin::where('email', $request->email)->first();

    //     if (!$admin || !Hash::check($request->password, $admin->password)) {
    //         // Log the error
    //         Log::error('Login failed for email: ' . $request->email);

    //         throw ValidationException::withMessages([
    //             'email' => ['The provided credentials are incorrect.'],
    //         ]);
    //     }

    //     // Generate a token with remember me functionality
    //     $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

    //     if ($request->remember_me) {

    //         $cookie = cookie('admin_token', $token, 43200); 
    //         return response()->json([
    //             'admin' => $admin,
    //             'token' => $token
    //         ], 200)->cookie($cookie); 
    //     }

    //     // Default case without remember me
    //     return response()->json([
    //         'admin' => $admin,
    //         'token' => $token
    //     ], 200);
    // }


    // public function login(Request $request) {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $admin = Admin::where('email', $request->email)->first();

    //     if (!$admin || !Hash::check($request->password, $admin->password)) {
    //         Log::error('Login failed for email: ' . $request->email);

    //         throw ValidationException::withMessages([
    //             'email' => ['The provided credentials are incorrect.'],
    //         ]);
    //     }

    //     // Generate a token for authentication
    //     $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

    //     // Save remember token if "Remember Me" is checked
    //     if ($request->remember_me) {
    //         $admin->remember_token = $token;
    //         $admin->save();

    //         // Set token in a long-lived cookie
    //         $cookie = cookie('admin_token', $token, 43200); // 30 days
    //         return response()->json([
    //             'admin' => $admin,
    //             'token' => $token
    //         ], 200)->cookie($cookie);
    //     }

    //     return response()->json([
    //         'admin' => $admin,
    //         'token' => $token
    //     ], 200);
    // }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user(); // Get the authenticated admin

            return response()->json([
                'admin' => $admin,
            ], 200);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }


    public function logout() {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }

    public function checkAuth(Request $request) {
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
    public function me(Request $request) {
        return response()->json($request->user());
    }
}
