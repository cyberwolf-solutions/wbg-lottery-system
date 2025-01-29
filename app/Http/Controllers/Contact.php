<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;

class Contact extends Controller
{
    public function index()
    {
        return Inertia::render('User/Contact', [
            'status' => session('status'),
        ]);
    }
}
