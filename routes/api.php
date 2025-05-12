<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;
use \App\Http\Controllers\API\ProfileController;
use Laravel\Passport\Passport;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::get('/test-hash', function () {
    $user = User::where('email', 'fabio@example.com')->first();
    if ($user) {
        $passwordFromPostman = 'password'; // La contraseña que estás enviando en Postman
        if (Hash::check($passwordFromPostman, $user->password)) {
            return response()->json(['message' => 'Password is correct!']);
        } else {
            return response()->json(['message' => 'Password is incorrect!']);
        }
    } else {
        return response()->json(['message' => 'User not found!']);
    }
});

Route::post("register", [AuthController::class, 'register']);

Route::post("login", [AuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('events', EventController::class);

    Route::get('/users/profile', [ProfileController::class, 'show']);

    Route::put('/users/profile', [ProfileController::class, 'update']);

    Route::post('users/logout', [AuthController::class, 'logout']);

    Route::post('events/{id_event}/users', [EventController::class, 'attendEvent']);
    Route::delete('events/{id_event}/users', [EventController::class, 'unattendEvent']);
});
