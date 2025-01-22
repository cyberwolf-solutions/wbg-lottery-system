<?php

namespace App\Http\Controllers;
use Inertia\Inertia;

use Illuminate\Http\Request;

class LatestResults extends Controller
{
    public function index()
    {
        return Inertia::render('User/LatestResults', [
            'status' => session('status'),
        ]);
    }
}
