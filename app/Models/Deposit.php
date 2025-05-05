<?php

// app/Models/Deposit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deposit extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "deposits";

    protected $fillable = [
        'wallet_id',
        'amount',
        'deposite_type',
        'deposit_date',
        'reference',
        'image',
        'status',

        'decline_reason'
    ];
    protected $dates = ['deleted_at'];

    // Relationship: A deposit belongs to a wallet
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Wallet::class, 'id', 'id', 'wallet_id', 'user_id');
    }
}
