<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    
    public function run(): void
    {

        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('1234'),
            'is_super_admin' => true,
        ]);


        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);

   
        $admin->assignRole($superAdminRole);
    }
}
