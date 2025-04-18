<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Notices;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_affiliate_link',
        'affiliate_link',
        'image',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
    public function pickedNumbers()
    {
        return $this->hasMany(PickedNumber::class);
    }


    public function affiliates()
    {
        return $this->hasMany(Affiliate::class, 'user_id');
    }

    public function referredUsers()
    {
        return $this->hasMany(Affiliate::class, 'affiliate_user_id');
    }

    public function notices()
    {
        return $this->hasMany(Notices::class);
    }
}
