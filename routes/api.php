<?php

use App\Http\Controllers\AdminDashboardController;
use Inertia\Inertia;
use App\Models\Lotteries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\LotteriesController;
use App\Http\Controllers\NumberPickController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\LotteryListController;
use App\Http\Controllers\PurchaseListController;
use Illuminate\Contracts\Foundation\Application;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\CreditRequestController;
use App\Http\Controllers\WalletHistoryController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\LotteryDashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\WinnersController;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])->middleware('recaptcha');;

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login')->middleware('recaptcha');;

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    // Route::post('/pick-number', [LotteryDashboardController::class, 'pickNumber']);
});


Route::middleware(['web'])->group(function () {
    Route::post('/pick-number', [NumberPickController::class, 'pickNumber']);
    Route::post('/checkout', [NumberPickController::class, 'checkout']);
    Route::post('/delete-picked-numbers', [NumberPickController::class, 'cancel']);

    Route::post('/deactivate-dashboard', [LotteriesController::class, 'deactivate']);

    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/request', [WalletCOntroller::class, 'request'])->name('wallet.request');
    Route::post('/wallet/withdraw', [WalletCOntroller::class, 'withdraw'])->name('wallet.withdraw');

    Route::get('/picked-numbers/{dashboardId}', [LotteriesController::class, 'getPickedNumbers']);

});





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
    Route::get('/lotteriesdropdown', function () {
        return Lotteries::all();
    });






    Route::prefix('admin')->group(function () {

        Route::get('/login', [AdminAuthController::class, 'index']);

        Route::post('/login', [AdminAuthController::class, 'login']);
        Route::middleware('admin')->group(function () {

            Route::middleware('auth:sanctum')->group(function () {
                Route::post('/logout', [AdminAuthController::class, 'logout']);
                Route::get('/me', [AdminAuthController::class, 'me']);
            });



            Route::get('/check-auth', [AdminAuthController::class, 'checkAuth']);




            //sidebar
            Route::get('/sidebar/lotteries', function () {
                return Lotteries::all();
            });

            //lotteies 
            Route::post('/lottery/create', [LotteryListController::class, 'store']);
            Route::get('/list', [LotteryListController::class,  'index']);
            Route::put('/lottery/update/{id}', [LotteryListController::class, 'update']);
            Route::delete('/lottery/delete/{id}', [LotteryListController::class, 'destroy']);





            //dashboards
            Route::get('/lottery/dashboard/{id}',  [LotteryDashboardController::class, 'index']);
            Route::post('/dashboard/create', [LotteryDashboardController::class, 'store']);



            //purchase List
            Route::get('/purchase/{id}', [PurchaseListController::class, 'index']);

            //creditreq
            Route::get('/creditReq', [CreditRequestController::class, 'index']);
            Route::get('/transactions', [WithdrawalController::class, 'index']);
            Route::post('/creditReq/approve/{id}', [CreditRequestController::class, 'approve']);
            Route::post('/creditReq/decline/{id}', [CreditRequestController::class, 'decline']);

            Route::post('/withdraw/approve/{id}', [WithdrawalController::class, 'approve']);
            Route::post('/withdraw/decline/{id}', [WithdrawalController::class, 'decline']);

            //results and winners
            Route::get('/results' , [ResultsController::class , 'index']);
            Route::post('/results/store' , [ResultsController::class , 'store']);
            Route::get('/adminWin/{id}' , [WinnersController::class , 'index']);



            //wallet history
            Route::get('/walletHistory' , [WalletHistoryController::class , 'index']);

            //dashboard
            Route::get('/api/admin/adminDash' , [AdminDashboardController::class , 'index']);



            Route::get('/dashboard', function () {
                return Inertia::render('AdminDashboard/Dashboard');
            });


            Route::get('/adminLot', function () {
                return Inertia::render('AdminDashboard/Lotteries');
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
});
