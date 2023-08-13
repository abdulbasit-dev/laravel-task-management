<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Artisan;

//API V1
Route::group(['prefix' => 'v1'], function () {
    /*======PUBLIC ROUTES=====*/

    //Clear all cache
    Route::get('/clear-all-cache', function () {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('route:clear');
        Artisan::call('clear-compiled');

        return "cache cleared";
    });

    // auth
    Route::group(["prefix" => "auth"], function () {
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:60,10');;
    });

    /*=====PROTECTED ROUTES=====*/
    Route::group(['middleware' => 'auth:sanctum'], function () {
        // auth
        Route::group(["prefix" => "auth"], function () {
            Route::post("logout", [AuthController::class, 'logout']);
        });

        //profile
        Route::get('/profiles', [ProfileController::class, 'showProfile']);
        Route::put('/profiles/update-info', [ProfileController::class, 'updateInfo']);
        Route::post('/profiles/update-image', [ProfileController::class, 'updateImage']);

        // users

        // tasks

        // subtasks

        // task transitions

    });
});

// CAllBACK ROUTES
Route::fallback(function () {
    return response()->json([
        'result' => false,
        'status' => 404,
        'message' => "Invalid route.",
    ], 404);
});
