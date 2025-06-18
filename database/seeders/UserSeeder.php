<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'Chami One',
            'email' => 'chami@gmail.com',
            'user_affiliate_link' => 'null',
            'password' => bcrypt('password'),
           
        ]);
    }
}
