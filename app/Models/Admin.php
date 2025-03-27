<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable, HasRoles;
    
    // protected $table = 'admin';
    protected $guard  = 'admin';
    protected $table = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

