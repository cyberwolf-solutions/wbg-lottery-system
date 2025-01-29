<?php

namespace App\Http\Controllers;
use Inertia\Inertia;

use Inertia\Inertia;
use Illuminate\Http\Request;

class HowItWorks extends Controller
{
    public function index()
    {
        return Inertia::render('User/HowItWorks', [
            'status' => session('status'),
        ]);
    }
}
