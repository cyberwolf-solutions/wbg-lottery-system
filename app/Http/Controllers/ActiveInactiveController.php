<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
use App\Http\Controllers\Controller;

class ActiveInactiveController extends Controller
{
    // public function deactiveLotteryDashboardsReport()
    // {
    //     $dashboards = LotteryDashboards::with('lottery')
    //         ->where('status', 'deactive')
    //         ->get()
    //         ->map(function ($dashboard) {
    //             return [
    //                 'id' => $dashboard->id,
    //                 'lottery' => $dashboard->lottery ? ['name' => $dashboard->lottery->name] : null,
    //                 'dashboard' => $dashboard->dashboard,
    //                 'price' => $dashboard->price,
    //                 'date' => $dashboard->date,
    //                 'draw_number' => $dashboard->draw_number,
    //                 'status' => $dashboard->status,
    //                 'created_by' => $dashboard->created_by,
    //                 'created_at' => $dashboard->created_at,
    //             ];
    //         });

    //     return Inertia::render('AdminDashboard/Expired', [
    //         'dashboards' => $dashboards
    //     ]);
    // }
}
