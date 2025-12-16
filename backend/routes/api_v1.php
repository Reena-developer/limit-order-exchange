<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\TradeController;

/*
|--------------------------------------------------------------------------
| Public Auth Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
    });

    Route::get('profile', [ProfileController::class, 'show']);

    Route::prefix('orders')->controller(OrderController::class)->name('orders.')->group(function() {
        Route::get('/', 'index');  
        Route::post('/', 'store');
        Route::post('{id}/cancel', 'cancel');
    });

    Route::get('/trades', [TradeController::class, 'index']);
});
