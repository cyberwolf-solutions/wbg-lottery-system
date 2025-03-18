<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletAdress extends Model
{
    //
    protected $table = "wallet_address";
    //
    protected $fillable = [
        'address',

    ];
}
