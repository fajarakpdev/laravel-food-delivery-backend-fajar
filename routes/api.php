<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\MeController;
use App\Http\Controllers\API\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('login', LoginController::class);
    Route::controller(RegisterController::class)->group(function () {
        Route::post('register/user', 'registerUser');
        Route::post('register/driver', 'registerDriver');
        Route::post('register/restaurant', 'registerRestaurant');
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('me', MeController::class);
//        Route::post('logout', [LoginController::class, 'logout']);
    });
});
