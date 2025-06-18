<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Admin;
use App\Models\Lotteries;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    public function player()
    {
        $users = User::with(['wallet', 'affiliates'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'wallet' => $user->wallet ? ['available_balance' => $user->wallet->available_balance] : null,
                    'affiliates' => $user->affiliates,
                    'status' => $user->status,
                    'created_at' => $user->created_at,
                ];
            });

        return Inertia::render('AdminDashboard/UserReport', [
            'users' => $users
        ]);
    }

    public function adminReport()
    {
        $admins = Admin::with('roles')
        ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($admin) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'roles' => $admin->roles,
                    'is_super_admin' => $admin->is_super_admin,
                    'created_at' => $admin->created_at,
                ];
            });

        return Inertia::render('AdminDashboard/AdminReport', [
            'admins' => $admins
        ]);
    }

    public function lotteriesReport()
    {
        $lotteries = Lotteries::with(['dashboards', 'winners'])
            ->orderBy('created_at', 'asc')

            ->get()
            ->map(function ($lottery) {
                return [
                    'id' => $lottery->id,
                    'name' => $lottery->name,
                    'description' => $lottery->description,
                    'dashboards' => $lottery->dashboards,
                    'winners' => $lottery->winners,
                    'created_by' => $lottery->creator ? $lottery->creator->name : 'N/A',
                    'created_at' => $lottery->created_at,
                ];
            });

        return Inertia::render('AdminDashboard/LoteryReport', [
            'lotteries' => $lotteries
        ]);
    }

    public function deactiveLotteryDashboardsReport()
    {
        $dashboards = LotteryDashboards::with('lottery')
            ->whereIn('status', ['deactive', 'closed'])
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($dashboard) {
                return [
                    'id' => $dashboard->id,
                    'lottery' => $dashboard->lottery ? ['name' => $dashboard->lottery->name] : null,
                    'dashboard' => $dashboard->dashboard,
                    'price' => $dashboard->price,
                    'date' => $dashboard->date,
                    'draw_number' => $dashboard->draw_number,
                    'status' => $dashboard->status,
                    'created_by' => $dashboard->created_by,
                    'created_at' => $dashboard->created_at,
                ];
            });

        return Inertia::render('AdminDashboard/Expired', [
            'dashboards' => $dashboards
        ]);
    }

    public function cancelledLotteryDashboardsReport()
    {
        $dashboards = LotteryDashboards::with('lottery')
            ->where('status', 'cancelled')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($dashboard) {
                return [
                    'id' => $dashboard->id,
                    'lottery' => $dashboard->lottery ? ['name' => $dashboard->lottery->name] : null,
                    'dashboard' => $dashboard->dashboard,
                    'price' => $dashboard->price,
                    'date' => $dashboard->date,
                    'draw_number' => $dashboard->draw_number,
                    'status' => $dashboard->status,
                    'created_by' => $dashboard->created_by,
                    'created_at' => $dashboard->created_at,
                ];
            });

        return Inertia::render('AdminDashboard/Cancelled', [
            'dashboards' => $dashboards
        ]);
    }
    public function refund()
    {
        $refund = Transaction::with([
            'wallet.user',
            'lottery',
            'lotteryDashboard'
        ])->where('type', 'refund')->get();

        Log::info('Refund Report:', ['refund' => $refund]);

        return Inertia::render('AdminDashboard/refundReport', [
            'refunds' => $refund
        ]);
    }
}
