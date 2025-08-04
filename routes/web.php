<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\WorkApplicationController;
use App\Models\Application;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WorkAppliedNotification;  
use App\Models\User;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\PointPurchaseController;
use App\Http\Controllers\StripeWebhookController;

use App\Http\Controllers\PointLogController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ChatController;

Route::get('/riyou',[MainController::class, 'riyou'])->name('riyou');
Route::get('/privacy',[MainController::class, 'privacy'])->name('privacy');
Route::get('/unei',[MainController::class, 'unei'])->name('unei');
// routes/web.php
Route::get('/my-applications', [ApplicationController::class, 'myApplications'])->name('applications.mine');

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->withoutMiddleware(ValidateCsrfToken::class);

Route::middleware(['auth'])->group(function () {

    Route::get('/points/buy', [PointPurchaseController::class, 'index'])->name('points.buy');
    Route::post('/points/checkout', [PointPurchaseController::class, 'checkout'])->name('points.checkout');
    Route::get('/points/checkout/success', [PointPurchaseController::class, 'success'])->name('points.checkout.success');
    Route::get('/points/checkout/cancel', [PointPurchaseController::class, 'cancel'])->name('points.checkout.cancel');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/points/history', [PointLogController::class, 'index'])->name('points.history');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/works/{work}/apply', [WorkApplicationController::class, 'store'])->name('works.apply');
});



Route::middleware(['auth'])->group(function () {
    Route::resource('works', WorkController::class);
    Route::post('/works', [WorkController::class, 'store'])->name('works.store');
    Route::get('/works/{work}', [WorkController::class, 'show'])->name('works.show');
    // 自分が出した仕事の詳細＆応募者一覧
    Route::get('/works/manage/{id}', [WorkController::class, 'manageShow'])->name('works.manage.show');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::get('/chat/{application}', [ChatController::class, 'show'])->name('chat.with');
    Route::post('/chat/{application}', [ChatController::class, 'send'])->name('chat.send');

});


Route::get('/',[MainController::class, 'index'])->name('dashboard');

Route::get('/dashboard', [MainController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
