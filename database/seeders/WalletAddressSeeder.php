<?php

namespace Database\Seeders;

use App\Models\WalletAdress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $bank = WalletAdress::create([
            'address' => '992810904',
            
        ]);
    }
}
