<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class roleController extends Controller
{
    //

    public function index()
    {

        $roles = Role::with('permissions')
            ->where('guard_name', 'admin')
            ->get()
            ->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name')->join(', '),
                    'created_at' => $role->created_at->format('Y-m-d H:i:s'),
                ];
            });
        // dd($roles);
        return Inertia::render('AdminDashboard/Roles', [
            'roles' => $roles
        ]);
    }

    public function add()
    {
        $permissions = Permission::where('guard_name', 'admin')
            ->get()
            ->groupBy(function ($permission) {
                // Extract module name from permission (e.g., 'manage users' => 'users')
                $parts = explode(' ', $permission->name);
                return $parts[1]; // The second word is typically the module
            })
            ->map(function ($permissions, $module) {
                return [
                    'name' => ucfirst($module),
                    'permissions' => $permissions->map(function ($permission) {
                        return [
                            'name' => $permission->name,
                            'action' => explode(' ', $permission->name)[0] // First word is action
                        ];
                    })
                ];
            })
            ->values();

        return Inertia::render('AdminDashboard/CreateRole', [
            'permissionModules' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        // Log::info('Role creation initiated', [
        //     'user_id' => auth()->id(),
        //     'request_data' => $request->except('_token')
        // ]);

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        Log::info('Validation passed', ['validated_data' => $validated]);

        try {
            // Create role
            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'admin'
            ]);

            Log::info('New role created', [
                'role_id' => $role->id,
                'role_name' => $role->name
            ]);

            // Sync permissions
            $role->syncPermissions($validated['permissions']);

            Log::info('Permissions assigned', [
                'role_id' => $role->id,
                'permissions_count' => count($validated['permissions']),
                'permissions' => $validated['permissions']
            ]);

            // Get updated roles list
            $roles = Role::with('permissions')
                ->where('guard_name', 'admin')
                ->get()
                ->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'permissions' => $role->permissions->pluck('name')->join(', '),
                        'created_at' => $role->created_at->format('Y-m-d H:i:s'),
                    ];
                });

            Log::info('Role list refreshed', ['total_roles' => count($roles)]);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            Log::info('Role creation failed', [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create role',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
