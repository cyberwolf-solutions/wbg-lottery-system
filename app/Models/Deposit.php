<?php

// app/Models/Deposit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;
    protected $table = "deposits";

    protected $fillable = [
        'wallet_id',
        'amount',
        'description',
        'deposit_date',
        'reference',
        'image',
        'status'
    ];

    // Relationship: A deposit belongs to a wallet
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
