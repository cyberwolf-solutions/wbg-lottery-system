<?php

namespace App\Http\Controllers;
use Inertia\Inertia;

use Illuminate\Http\Request;

class LangingPage extends Controller
{
    public function index()
    {
        return Inertia::render('User/lotteryPage', [
            'status' => session('status'),
        ]);
    }

    public function faq()
    {
        return Inertia::render('User/faq', [
            'status' => session('status'),
        ]);
    }

}
