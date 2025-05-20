<?php

use Inertia\Inertia;
use App\Http\Controllers\Contact;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\HowItWorks;
use App\Http\Controllers\UserPannel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\LangingPage;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\LatestResults;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\PrizesController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WinnerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\LotteriesController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\LandingLotteryController;


// Route::get('/', function () {
//     return Inertia::render('LandingPage');
// })->name('landing');

Route::get('/', [LandingController::class, 'index'])->name('landing');


Route::get('/dashboard', [HomeController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/image', [ProfileController::class, 'updateImage'])
        ->name('profile.image.update');
});


//affiliate 
Route::get('/ref/{code}', [LandingController::class, 'index'])->name('referral');

// ->middleware(['auth']);

Route::get('/prize', [PrizesController::class, 'index'])->name('prize.index');
Route::get('/winners', [PrizesController::class, 'index'])->name('prize.index');
Route::get('/HowItWorks', [HowItWorks::class, 'index'])->name('hiw.index');
Route::get('/contact', [Contact::class, 'index'])->name('contact.index');
Route::get('/latest', [LatestResults::class, 'index'])->name('latest.index');
Route::get('/winners', [WinnerController::class, 'index'])->name('winner.index');
Route::get('/affiliate', [AffiliateController::class, 'index'])->name('affiliate.index');
Route::get('/landinglottery', [LangingPage::class, 'index'])->name('lottery');
Route::get('/faq', [LangingPage::class, 'faq'])->name('faq');
Route::get('/terms', [UserPannel::class, 'terms'])->name('terms');
Route::get('/privacy', [UserPannel::class, 'privacy'])->name('privacy');
Route::get('/landinglotteries/list', [LandingLotteryController::class, 'index'])->name('landinglottery.index');


Route::post('/send-contact-email', [ContactController::class, 'sendEmail']);


// Route::get('/lottery/{id}', [LotteriesController::class, 'index']);
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


Broadcast::routes(['middleware' => ['web', 'auth:admin']]);
Route::post('/user/broadcasting/auth', function () {
    return Broadcast::auth(request());
})->middleware(['web', 'auth']);
// Route::post('/broadcasting/auth', function () {
//     // Log the request details
//     Log::info('Broadcasting auth request received', [
//         'admin_authenticated' => Auth::guard('admin')->check(),
//         'admin_id' => Auth::guard('admin')->user()?->id,
//         'channel_name' => request()->input('channel_name'),
//         'session_id' => session()->getId(),
//         'request_headers' => request()->headers->all(),
//         'ip_address' => request()->ip(),
//     ]);

//     // Delegate to the default broadcasting authorization logic
//     return Broadcast::auth(request());
// })->middleware(['web', 'auth:admin']);




Route::get('/test-email', function () {
    $recipientEmail = 'nipun.sankalana@gmail.com';

    Log::info('Attempting to send test email', [
        'recipient' => $recipientEmail,
        'mail_config' => [
            'driver' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name')
        ],
        'time' => now()->toDateTimeString()
    ]);

    try {
        Mail::raw('Test email content', function ($message) use ($recipientEmail) {
            $message->to($recipientEmail)
                ->subject('Test Email');

            Log::info('Email message prepared', [
                'recipient' => $recipientEmail,
                'subject' => 'Test Email',
                'time' => now()->toDateTimeString()
            ]);
        });

        Log::info('Email sent successfully', [
            'recipient' => $recipientEmail,
            'time' => now()->toDateTimeString()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Email sent successfully',
            'details' => [
                'to' => $recipientEmail,
                'config' => config('mail.mailers.smtp')
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Failed to send test email', [
            'recipient' => $recipientEmail,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'time' => now()->toDateTimeString()
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Email sending failed',
            'error' => $e->getMessage(),
            'details' => [
                'to' => $recipientEmail,
                'config' => config('mail.mailers.smtp')
            ]
        ], 500);
    }
});


require __DIR__ . '/auth.php';
