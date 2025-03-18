<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Lottery;
use App\Models\Lotteries;
use App\Models\PickedNumber;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
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

        Log::info($lotteries);



        $lotterydashboards = LotteryDashboards::where('lottery_id', $id)
            ->where('status', 'active')
            ->get()
            ->groupBy('dashboard')
            ->map(function ($group) {
                return $group->take(10);
            })
            ->collapse();



        $pickedNumbers = PickedNumber::whereIn('lottery_dashboard_id', $lotterydashboards->pluck('id'))
            ->get()
            ->map(function ($item) {
                return [
                    'picked_number' => $item->picked_number,
                    'lottery_dashboard_id'=>$item->lottery_dashboard_id
                ];
            })
            ->toArray();

        // dd($pickedNumbers);


        // Pass the lottery data to the Inertia page
        return Inertia::render('User/lottery', [
            'lotterie' => $lotteries,
            'lotterydashboards' => $lotterydashboards,
            'pickedNumbers' => $pickedNumbers,
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
}
