<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lotteries;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    //
    public function index(){
        $lotteries = Lotteries::all();

        
    }
}
