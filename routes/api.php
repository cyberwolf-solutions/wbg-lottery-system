<?php

use Inertia\Inertia;
use App\Models\Lotteries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\roleController;
use App\Http\Controllers\testController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FundsController;
use App\Http\Controllers\BackUpController;
use App\Http\Controllers\HoidayController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\MessageCOntroller;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\WinnersController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\LotteriesController;
use App\Http\Controllers\NumberPickController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\AdminNoticeController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\bankDetailsController;
use App\Http\Controllers\LotteryListController;
use App\Http\Controllers\AdminPlayersController;
use App\Http\Controllers\PurchaseListController;
use Illuminate\Contracts\Foundation\Application;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\CreditRequestController;
use App\Http\Controllers\WalletHistoryController;
use App\Http\Controllers\ActiveInactiveController;
use App\Http\Controllers\AdminAffiliateController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\DashboardChangeController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\LotteryDashboardController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\SubscriptionEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])->middleware('recaptcha');


    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login')->middleware('recaptcha');


    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});


Route::middleware(['web'])->group(function () {
    Route::post('/pick-number', [NumberPickController::class, 'pickNumber']);
    Route::post('/digit-pick-number', [NumberPickController::class, 'digitpickNumber']);
    Route::post('/checkout', [NumberPickController::class, 'checkout']);
    Route::post('/digitcheckout', [NumberPickController::class, 'digitcheckout']);
    Route::post('/delete-picked-numbers', [NumberPickController::class, 'cancel']);

    Route::post('/delete-digit-picked-numbers', [NumberPickController::class, 'digitcancel']);

    Route::post('/deactivate-dashboard', [LotteriesController::class, 'deactivate']);

    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/request', [WalletCOntroller::class, 'request'])->name('wallet.request');
    Route::post('/wallet/withdraw', [WalletCOntroller::class, 'withdraw'])->name('wallet.withdraw');

    Route::get('/picked-numbers/{dashboardId}', [LotteriesController::class, 'getPickedNumbers']);

    // routes/api.php
    Route::get('/notifications', function (Request $request) {
        return response()->json($request->user()->notifications);
    })->middleware('auth:sanctum');


    Route::get('/messages', [MessageController::class, 'userindex']);
    Route::post('/messages/store', [MessageController::class, 'store']);


    Route::post('/subscribe', [SubscriptionEmailController::class, 'store']);
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
    Route::get('/digitlottery/{id}', [LotteriesController::class, 'digitshow']);
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);










    Route::get("/customers", function () {
        return Inertia::render("AdminDashboard/Customers");
    });

    Route::get("/users", function () {
        return Inertia::render("AdminDashboard/Users");
    });


    Route::get('/lotteriesdropdown', function () {
        return Lotteries::all();
    });


    Route::get('/admin/permissions', function () {
        $admin = Auth::guard('admin')->user();

        Log::info('Admin Accessed Permissions:', [
            'id' => $admin ? $admin->id : null,
            'name' => $admin ? $admin->name : null,
            'email' => $admin ? $admin->email : null,
            'permissions' => $admin ? $admin->roles->flatMap->permissions->pluck('name')->unique() : []
        ]);

        return response()->json([
            'admin_name' => $admin ? $admin->name : null,
            'permissions' => $admin ? $admin->roles->flatMap->permissions->pluck('name')->unique() : []
        ]);
    })->middleware('auth:admin');


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

            Route::get('/lottery/digitdashboard/{id}',  [LotteryDashboardController::class, 'OneDigit']);

            Route::post('/digitdashboard/create', [LotteryDashboardController::class, 'digitstore']);




            //purchase List
            Route::get('/purchase/{id}', [PurchaseListController::class, 'index']);
            Route::get('/digit-purchase/{id}', [PurchaseListController::class, 'digitindex']);


            //creditreq
            Route::get('/creditReq', [CreditRequestController::class, 'index']);
            Route::get('/transactions', [WithdrawalController::class, 'index']);
            Route::post('/creditReq/approve/{id}', [CreditRequestController::class, 'approve']);
            Route::post('/creditReq/decline/{id}', [CreditRequestController::class, 'decline']);

            Route::post('/withdraw/approve/{id}', [WithdrawalController::class, 'approve']);
            Route::post('/withdraw/decline/{id}', [WithdrawalController::class, 'decline']);

            //results and winners
            Route::get('/results', [ResultsController::class, 'index']);

            Route::post('/results/store', [ResultsController::class, 'store']);
            Route::post('/digitresults/store', [ResultsController::class, 'digitstore']);



            Route::get('/adminWin/{id}', [WinnersController::class, 'index']);
            Route::get('/adminWin-digit/{id}', [WinnersController::class, 'digitindex']);



            Route::get('/digit-results', [ResultsController::class, 'digitindex']);

            Route::post('/digit-results/store', [ResultsController::class, 'digitstore']);
            // Route::get('/adminWin/{id}', [WinnersController::class, 'index']);


            //wallet history
            Route::get('/walletHistory', [WalletHistoryController::class, 'index']);

            //dashboard
            Route::get('/dashboard', [AdminDashboardController::class, 'index']);

            //admin logout
            Route::post('/logout', [AdminAuthController::class, 'logout']);


            Route::get('/Roles', [roleController::class, 'index']);
            Route::get('/Roles/Add', [roleController::class, 'add']);
            Route::post('/admin/roles', [roleController::class, 'store']);



            Route::get('/users', [AdminUserController::class, 'index']);
            Route::post('/users', [AdminUserController::class, 'store']);


            Route::get('/players', [AdminPlayersController::class, 'index']);

            Route::put('/users/{user}/activate', [AdminPlayersController::class, 'activate'])
                ->name('admin.users.activate');

            Route::put('/users/{user}/deactivate', [AdminPlayersController::class, 'deactivate'])
                ->name('admin.users.deactivate');




            Route::get('/affiliate', [AdminAffiliateController::class, 'index']);


            Route::get('/notices', [AdminNoticeController::class, 'index']);
            Route::post('/notices/store', [AdminNoticeController::class, 'store']);



            Route::get('/player-reports', [ReportsController::class, 'player']);
            Route::get('/Admin-reports', [ReportsController::class, 'adminReport']);
            Route::get('/Lottery-reports', [ReportsController::class, 'lotteriesReport']);
            Route::get('/Lottery-Expired', [ReportsController::class, 'deactiveLotteryDashboardsReport']);
            Route::get('/Lottery-Cancelled', [ReportsController::class, 'cancelledLotteryDashboardsReport']);
            Route::get('/refund', [ReportsController::class, 'refund']);
            Route::get('/cancelled', [ReportsController::class, 'cancelled']);
            Route::get('/User-Picks', [ReportsController::class, 'UserPicks']);
            Route::get('/Picked-Numbers', [ReportsController::class, 'PickedNumbers']);



            Route::get('/bank-details', [bankDetailsController::class, 'index']);
            Route::put('/bank-details/update', [BankDetailsController::class, 'update'])->name('bank.update');


            Route::get('/Funds', [FundsController::class, 'index']);

            Route::get('/email', [EmailController::class, 'index']);
            Route::post('/send-email', [EmailController::class, 'sendEmail']);

            Route::post('/funds/{user}/update', [FundsController::class, 'updateWallet'])->name('admin.funds.update');


            Route::get('/holiday', [HoidayController::class, 'index']);
            Route::post('/holiday/store', [HoidayController::class, 'store']);

            Route::get('/edit/Dashboard', [DashboardChangeController::class, 'index']);
            Route::put('/dashboards/{dashboard}', [DashboardChangeController::class, 'update'])->name('admin.dashboards.update');

            // Route::get('/Lottery-Expired', [ActiveInactiveController::class, 'deactiveLotteryDashboardsReport']);



            // Route::get('/dashboard', function () {
            //     return Inertia::render('AdminDashboard/Dashboard');
            // });


            Route::get('/adminLot', function () {
                return Inertia::render('AdminDashboard/Lotteries');
            });


            //messages
            Route::get('/messages', [MessageCOntroller::class, 'index']);
            Route::post('/messages/store', [MessageCOntroller::class, 'adminStore']);


            //backup
            Route::get('/backup', [BackUpController::class, 'index']);
            Route::get('/backup/db', [BackupController::class, 'backup'])
                ->name('admin.backup');

            // Route::get("/customers", function () {
            //     return Inertia::render("AdminDashboard/Customers");
            // });
            Route::get('/notifications', [AdminNotificationController::class, 'index']);
            Route::post('/notifications/mark-as-read', [AdminNotificationController::class, 'markAsRead']);
        });
    });
});
