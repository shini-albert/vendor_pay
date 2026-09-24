<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\TestpageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/test', [AuthController::class, 'showTestPage'])->name('test');
});
Route::get('/test',[TestpageController::class,'test']

);
Route::get('/payment', [PaymentController::class, 'create'])->name('payment');
Route::post('/payments', [PaymentController::class, 'store'])->name('payment.store');
