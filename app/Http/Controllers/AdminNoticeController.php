<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Notices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPUnit\Framework\TestStatus\Notice;

class AdminNoticeController extends Controller
{
    //
    public function index()
    {
        $notices = Notices::with('user')->latest()->get();
        $users = User::select('id', 'name')->get();

        return Inertia::render('AdminDashboard/Notice', [
            'notices' => $notices,
            'users' => $users
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        Notices::create($validated);

        return response()->json(['success' => true]);
    }}
