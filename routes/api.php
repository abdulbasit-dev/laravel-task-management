<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\{
    ProjectController,
    RoleController,
    UserController,
    TaskStatusController,
    TaskController,
    SubtaskController,
};

use Illuminate\Support\Facades\Artisan;

//API V1
Route::group(['prefix' => 'v1', "middleware"=>["throttle:30,1"]], function () {
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
        Route::post('/login', [AuthController::class, 'login']);
    });

    /*=====PROTECTED ROUTES=====*/
    Route::group(['middleware' => 'auth:sanctum'], function () {
        // auth
        Route::group(["prefix" => "auth"], function () {
            Route::post("logout", [AuthController::class, 'logout']);
        });


        // roles
        Route::get("roles", RoleController::class);

        // users
        Route::apiResource('users', UserController::class);

        // projects
        Route::apiResource('projects', ProjectController::class);

        // task-statuses
        Route::get('task-statuses', TaskStatusController::class);

        // tasks
        Route::post('tasks/{task}/assign-task', [TaskController::class, 'assignTask']);
        Route::post('tasks/{task}/change-status', [TaskController::class, 'changeStatus']);
        Route::apiResource('tasks', TaskController::class);

        // subtasks
        Route::post('tasks/{task}/subtasks/{subtask}/assign-subtask', [SubTaskController::class, 'assignSubTask']);
        Route::apiResource('tasks.subtasks', SubtaskController::class);

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
