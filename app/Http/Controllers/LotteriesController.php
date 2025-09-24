<?php

namespace App\Http\Controllers;

use App\Models\DigitLotteryDashboard;
use App\Models\DigitPickedNumber;
use Inertia\Inertia;
use App\Models\Lottery;
use App\Models\Lotteries;
use App\Models\PickedNumber;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LotteriesController extends Controller
{


    public function index()
    {
        return Inertia::render();
    }


    public function show($id)
    {


        $lotteries = Lotteries::find($id);
        $user = Auth::user();
        $wallet = $user->wallet;
        // dd($wallet);

        Log::info($lotteries);


        // $lotterydashboards = LotteryDashboards::where('lottery_id', $id)->where('status', 'active')->with('lottery')->get();
        $lotterydashboards = LotteryDashboards::where('lottery_id', $id)
            ->where('status', 'active')
            ->with('lottery')
            ->orderBy('date', 'asc')
            ->get();
        // dd($lotterydashboards);

        $dashboardTypes = LotteryDashboards::where('lottery_id', $id)
            ->where('status', 'active')
            ->pluck('dashboardType')
            ->unique()
            ->values();





        // dd($dashboardTypes);
        $pickedNumbers = PickedNumber::whereIn('lottery_dashboard_id', $lotterydashboards->pluck('id'))
            ->get()
            ->map(function ($item) {
                return [
                    'picked_number' => $item->picked_number,
                    'lottery_dashboard_id' => $item->lottery_dashboard_id
                ];
            })
            ->toArray();

        // dd($pickedNumbers);


        // Pass the lottery data to the Inertia page
        return Inertia::render('User/lottery', [
            'lotterie' => $lotteries,
            'lotterydashboards' => $lotterydashboards,
            'pickedNumbers' => $pickedNumbers,
            'wallet' => $wallet,
            'dashboardTypes' => $dashboardTypes,
            'status' => session('status'),
        ]);
    }
    public function deactivate(Request $request)
    {
        $dashboard = LotteryDashboards::where('id', $request->dashboard_id)->first();

        if (!$dashboard) {
            return response()->json(['error' => 'Dashboard not found'], 404);
        }

        $dashboard->status = 'deactive';
        $dashboard->save();

        return response()->json(['message' => 'Dashboard deactivated successfully']);
    }




    public function digitshow($id)
    {


        $lotteries = Lotteries::find($id);
        $user = Auth::user();
        $wallet = $user->wallet;
        // dd($wallet);

        Log::info($lotteries);


        // $lotterydashboards = LotteryDashboards::where('lottery_id', $id)->where('status', 'active')->with('lottery')->get();
        $lotterydashboards = DigitLotteryDashboard::where('lottery_id', $id)
            ->where('status', 'active')
            ->with('lottery')
            ->orderBy('date', 'asc')
            ->get();
        // dd($lotterydashboards);

        $dashboardTypes = DigitLotteryDashboard::where('lottery_id', $id)
            ->where('status', 'active')
            ->pluck('dashboardType')
            ->unique()
            ->values();





        // dd($dashboardTypes);
        $pickedNumbers = DigitPickedNumber::whereIn('digit_lottery_dashboard_id', $lotterydashboards->pluck('id'))
            ->get()
            ->map(function ($item) {
                return [
                    'picked_number' => $item->picked_number,
                    'lottery_dashboard_id' => $item->digit_lottery_dashboard_id
                ];
            })
            ->toArray();

        // dd($pickedNumbers);


        // Pass the lottery data to the Inertia page
        return Inertia::render('User/digitlottery', [
            'lotterie' => $lotteries,
            'lotterydashboards' => $lotterydashboards,
            'pickedNumbers' => $pickedNumbers,
            'wallet' => $wallet,
            'dashboardTypes' => $dashboardTypes,
            'status' => session('status'),
        ]);
    }
}
