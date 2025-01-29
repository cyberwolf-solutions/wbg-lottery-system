<?php

namespace App\Http\Controllers;

use App\Models\Lottery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AffiliateController extends Controller
{
    public function index()
    {
        

        return Inertia::render('User/Affiliate', [
            'status' => session('status'),
        ]);
    }
}

