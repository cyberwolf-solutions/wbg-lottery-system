<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = Admin::with('roles')
            ->get()
            ->map(function ($admin) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'role' => $admin->roles->first()?->name ?? 'No Role',
                    'status' => $admin->is_super_admin ? 'Super Admin' : 'Active',
                    'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
                ];
            });

        $roles = Role::where('guard_name', 'admin')
            ->get()
            ->pluck('name');


        // dd($admins);
        return Inertia::render('AdminDashboard/Users', [
            'admins' => $admins,
            'roles' => $roles
        ]);
    }

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email', 
            'role' => 'required|string|exists:roles,name',
            'password' => 'required|string|min:8',
        ]);

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), 
        ]);

        $admin->assignRole($validated['role']);

        return response()->json(['success' => true, 'message' => 'User created successfully']);
    }
}
