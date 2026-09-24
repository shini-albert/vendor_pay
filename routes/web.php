<?php
use App\Http\Controllers\Controller;
use App\Http\Controllers\TestpageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test',[TestpageController::class,'test']

);
Route::get('/payment', [PaymentController::class, 'create'])->name('payment');
Route::post('/payments', [PaymentController::class, 'store'])->name('payment.store');