<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;
use \App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\EventFilterController;
use Laravel\Passport\Passport;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::get('/events/popular', [EventFilterController::class, 'popular']);
Route::get('/events/free', [EventFilterController::class, 'free']);
Route::get('/events/before-time', [EventFilterController::class, 'beforeTime']);
Route::get('/events/after-time', [EventFilterController::class, 'afterTime']);

Route::post("register", [AuthController::class, 'register']);

Route::post("login", [AuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('/events', EventController::class);

    Route::delete('/events/{event}', [EventController::class, 'destroy']);

    Route::get('/users/profile', [ProfileController::class, 'show']);

    Route::put('/users/profile', [ProfileController::class, 'update']);

    Route::post('/users/logout', [AuthController::class, 'logout']);

    Route::delete('/users/profile', [ProfileController::class, 'destroy']);

    Route::delete('/users/{user}', [ProfileController::class, 'destroyOther']);

    Route::post('/events/{id_event}/users', [EventController::class, 'attendEvent']);
    Route::delete('/events/{id_event}/users', [EventController::class, 'unattendEvent']);
  

});

