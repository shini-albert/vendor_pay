<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\TestpageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [TestpageController::class, 'test']);
Route::get('/login', [TestpageController::class, 'login']);