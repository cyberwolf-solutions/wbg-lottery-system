<?php

namespace App\Http\Controllers;
use Inertia\Inertia;

use Illuminate\Http\Request;

class LangingPage extends Controller
{
    public function index()
    {
        return Inertia::render('Components/Landing/lotteryPage', [
            'status' => session('status'),
        ]);
    }
}
