<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testController;
use App\Http\Controllers\LotteriesController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Contracts\Foundation\Application;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\LotteryDashboardController;
use App\Http\Controllers\LotteryListController;
use App\Http\Controllers\PublicController;
use App\Models\Lotteries;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



// Route::get('/landing-page-data', function () {
//     return response()->json([
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => \Illuminate\Foundation\Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });


// Route::get('/lottery/{id}', [LotteriesController::class, 'index']);
// Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
// Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


Route::middleware(['web'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::get('/landing-page-data', function () {
        return response()->json([
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => \Illuminate\Foundation\Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    });

    Route::get('/lottery/{id}', [LotteriesController::class, 'show']);
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

    Route::get('/adminDash', function () {
        return Inertia::render('AdminDashboard/Dashboard');
    });


    Route::get('/adminLot', function () {
        return Inertia::render('AdminDashboard/Lotteries');
    });

    Route::get('/adminWin', function () {
        return Inertia::render("AdminDashboard/Winners");
    });

    Route::get('/creditReq', function () {
        return Inertia::render("AdminDashboard/Credit");
    });

    Route::get('/transactions', function () {
        return Inertia::render("AdminDashboard/Transactions");
    });

    Route::get('/purchase', function () {
        return Inertia::render("AdminDashboard/Purchase");
    });

    Route::get('/walletHistory', function () {
        return Inertia::render("AdminDashboard/WalletHistory");
    });

    Route::get("/customers", function () {
        return Inertia::render("AdminDashboard/Customers");
    });

    Route::get("/users", function () {
        return Inertia::render("AdminDashboard/Users");
    });

    Route::get("/Roles", function () {
        return Inertia::render("AdminDashboard/Roles");
    });

    Route::get("/Roles/Add", function () {
        return Inertia::render("AdminDashboard/CreateRole");
    });



    //fetch lotteries to nav
    // Route::get('/lotteriesdropdown' ,[PublicController::class , 'index'] );
    Route::get('/lotteriesdropdown', function(){
        return Lotteries::all();
    });

    



    Route::prefix('admin')->group(function () {
        
        Route::get('/login/index', [AdminAuthController::class, 'index']);
        
        Route::post('/login', [AdminAuthController::class, 'login']);
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AdminAuthController::class, 'logout']);
            Route::get('/me', [AdminAuthController::class, 'me']);
            
        });



        //sidebar
        Route::get('/sidebar/lotteries', function(){
            return Lotteries::all();
        });

        //lotteies 
        Route::post('/lottery/create', [LotteryListController::class, 'store']);
        Route::get('/list' ,[LotteryListController::class ,  'index']);
        Route::put('/lottery/update/{id}', [LotteryListController::class, 'update']);
        Route::delete('/lottery/delete/{id}', [LotteryListController::class, 'destroy']);

        
        


        //dashboards
        Route::get('/lottery/dashboard/{id}',  [LotteryDashboardController::class, 'index']);    
        Route::post('/dashboard/create', [LotteryDashboardController::class, 'store']);


        Route::get('/dashboard', function () {
            return Inertia::render('AdminDashboard/Dashboard');
        });
    
    
        Route::get('/adminLot', function () {
            return Inertia::render('AdminDashboard/Lotteries');
        });
    
        Route::get('/adminWin', function () {
            return Inertia::render("AdminDashboard/Winners");
        });
    
        Route::get('/creditReq', function () {
            return Inertia::render("AdminDashboard/Credit");
        });
    
        Route::get('/transactions', function () {
            return Inertia::render("AdminDashboard/Transactions");
        });
    
        Route::get('/purchase', function () {
            return Inertia::render("AdminDashboard/Purchase");
        });
    
        Route::get('/walletHistory', function () {
            return Inertia::render("AdminDashboard/WalletHistory");
        });
    
        Route::get("/customers", function () {
            return Inertia::render("AdminDashboard/Customers");
        });
    
        Route::get("/users", function () {
            return Inertia::render("AdminDashboard/Users");
        });
    
        Route::get("/Roles", function () {
            return Inertia::render("AdminDashboard/Roles");
        });
    
        Route::get("/Roles/Add", function () {
            return Inertia::render("AdminDashboard/CreateRole");
        });
    
    });
});
