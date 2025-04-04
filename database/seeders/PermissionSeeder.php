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
            'view users',
            'view dashboard',
            'manage users',
            'view notices',
            'manage affiliates',
            'manage roles',
            'manage players',
            'manage lotteries',
            'manage dashboards',
            'view results',
            'manage winners',
            'manage credits',
            'manage withdrawals',
            'view purchases',
            'view wallet history'
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'admin']);
        }

        // Assign permissions to roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'admin']);
        $editorRole->syncPermissions(['view dashboard', 'view users', 'view results']);
    }
}
