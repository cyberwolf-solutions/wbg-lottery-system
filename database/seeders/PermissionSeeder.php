<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage users',
            'create users',
            'view users',
            'edit users',
            'delete users',
            'manage roles',
            'assign roles'
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'admin']);
        }

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'admin']);

        // Assign Permissions to Roles
        $adminRole->syncPermissions($permissions); // Assign all permissions to Admin
        $editorRole->syncPermissions(['view users']); // Editor can only view users
    }
}

