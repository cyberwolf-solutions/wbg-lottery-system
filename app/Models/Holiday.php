<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    //

    use SoftDeletes;

    protected $table = 'holiday';

    protected $fillable = [
        'name',
        'date',
    ];


   
    protected $casts = [
        'date' => 'date', 
    ];
    
    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
