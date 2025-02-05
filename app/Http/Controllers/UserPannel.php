<?php

namespace App\Http\Controllers;
use Inertia\Inertia;

use Illuminate\Http\Request;

class UserPannel extends Controller
{
    public function terms()
    {
        return Inertia::render('User/Terms', [
            'status' => session('status'),
        ]);
    }

    public function privacy()
    {
        return Inertia::render('User/Privacy', [
            'status' => session('status'),
        ]);
    }
}
