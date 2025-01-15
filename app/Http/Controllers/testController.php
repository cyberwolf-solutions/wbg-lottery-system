<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testController extends Controller
{
    public static function index(){
        return json_encode("hello");
    }
}
