<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Category\CategoryCrudController;
use App\Http\Controllers\Api\V1\Product\ProductCrudController;
use Illuminate\Support\Facades\Route;

Route::name('api.v1.')->group(function () {

    Route::prefix('v1')->group(function () {

        Route::post('login', LoginController::class)->name('login');
        Route::post('register', RegisterController::class)->name('register');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('user', function () {
                return response()->json([
                    'user' => request()->user()
                ]);
            });

            Route::apiResource('categories', CategoryCrudController::class);
            Route::apiResource('products', ProductCrudController::class);
        });

    });
});
