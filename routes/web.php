<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Models\Payment;


Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/payment', [PaymentController::class, 'create'])->name('payment');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payment.store');

    // Preview Approvals Page (Fetches payments directly from DB)
    Route::get('/approvals', function () {
        $payments = Payment::with(['vendor', 'workflow'])->latest()->get();
        return view('approvals', compact('payments'));
    })->name('approvals.index');
});