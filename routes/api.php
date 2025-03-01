<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Middleware\JwtMiddleware;

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::group(['prefix' => 'task'], function() {
        Route::apiResource('/', TaskController::class);
        Route::apiResource('store', TaskController::class);
        Route::apiResource('show', TaskController::class);
        Route::apiResource('update', TaskController::class);
        Route::apiResource('destroy', TaskController::class);
    });

    Route::group(['prefix' => 'project'], function() {
        Route::apiResource('/', ProjectController::class);
        Route::apiResource('store', ProjectController::class);
        Route::apiResource('show', ProjectController::class);
        Route::apiResource('update', ProjectController::class);
        Route::apiResource('destroy', ProjectController::class);
    });
});