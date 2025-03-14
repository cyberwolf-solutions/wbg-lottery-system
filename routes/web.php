<?php

use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\Contact;
use App\Http\Controllers\HowItWorks;
use App\Http\Controllers\LatestResults;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PrizesController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LotteriesController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\LangingPage;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\UserPannel;
use App\Http\Controllers\WinnerController;


Route::get('/', function () {
    return Inertia::render('LandingPage');
})->name('landing');




Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/prize', [PrizesController::class, 'index'])->name('prize.index');
Route::get('/HowItWorks', [HowItWorks::class, 'index'])->name('hiw.index');
Route::get('/contact', [Contact::class, 'index'])->name('contact.index');
Route::get('/latest', [LatestResults::class, 'index'])->name('latest.index');
Route::get('/winners', [WinnerController::class, 'index'])->name('winner.index');
Route::get('/affiliate', [AffiliateController::class, 'index'])->name('affiliate.index');
Route::get('/landinglottery', [LangingPage::class, 'index'])->name('lottery');
Route::get('/faq', [LangingPage::class, 'faq'])->name('faq');
Route::get('/terms', [UserPannel::class, 'terms'])->name('terms');
Route::get('/privacy', [UserPannel::class, 'privacy'])->name('privacy');

// Route::get('/lottery/{id}', [LotteriesController::class, 'index']);
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

require __DIR__.'/auth.php';
