<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\MeController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\UpdateLetLongController;
use App\Http\Controllers\API\GetRestaurantController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('login', LoginController::class);
    Route::controller(RegisterController::class)->group(function () {
        Route::post('register/user', 'registerUser');
        Route::post('register/driver', 'registerDriver');
        Route::post('register/restaurant', 'registerRestaurant');
    });

    Route::get('get-all-restaurant', GetRestaurantController::class);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::delete('logout', LogoutController::class);
        Route::get('me', MeController::class);
        Route::put('update-let-long', UpdateLetLongController::class);

        Route::apiResource('products', ProductController::class);

        Route::controller(OrderController::class)->group(function () {
            Route::get('order/user', 'orderHistory');
            Route::post('order/create', 'createOrder');
            Route::put('order/cancel{order}', 'cancelOrder');
            Route::put('order/update-purchase-status/{order}', 'updatePurchaseStatus');
            Route::get('order/restaurant', 'getOrderByStatusForRestaurant');
            Route::put('order/restaurant/update-status/{order}', 'updateOrderStatusForRestaurant');
//            getOrderStatusByDriver
            Route::post('order/get-order-status-by-driver', 'getOrderStatusByDriver');
            Route::get('order/driver', 'getOrderStatusReadyForDelivery');
            Route::put('order/driver/update-status/{order}', 'updateStatusForDriver');
        });
    });
});
