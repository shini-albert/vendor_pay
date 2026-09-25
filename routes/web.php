<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentApprovalController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/payment', [PaymentController::class, 'create'])->name('payment');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payment.store');

    
    Route::get('/approvals', [PaymentApprovalController::class, 'index'])->name('approvals.index');
    Route::post('/approvals/{id}/approve', [PaymentApprovalController::class, 'approve'])->name('payments.approve');
    Route::post('/approvals/{id}/reject', [PaymentApprovalController::class, 'reject'])->name('payments.reject');
});