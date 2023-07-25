<?php

use App\Http\Controllers\Api\V1\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::name('api.v1.')->group(function () {

    Route::prefix('v1')->group(function () {

        Route::post('register', RegisterController::class)->name('register');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('user', function () {
                return response()->json([
                    'user' => request()->user()
                ]);
            });
        });

    });
});
