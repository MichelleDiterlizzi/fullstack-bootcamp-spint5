<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post("register", [AuthController::class, 'register']);

Route::post("login", [AuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

Route::get('/events/categories/{id_category}', [EventController::class, 'eventsByCategory'])->name('categories.events');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('events', EventController::class);
});