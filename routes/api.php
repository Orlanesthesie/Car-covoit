<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route de test pour vérifier si le JWT est valide
Route::get('jwt-valid', function () {
    return response()->json(['valid' => auth()->check()]);
});

// Authentification routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

// User management routes
Route::middleware('auth:api', 'isAdmin')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
});


Route::middleware('auth:api',)->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    // Route::put('users/{id}', [UserController::class, 'update']);
    // Route::delete('users/{id}', [UserController::class, 'destroy']);
});

// Trip management routes
Route::middleware('auth:api')->group(function () {
    Route::apiResource('trips', TripController::class);
    Route::get('search', [TripController::class, 'search']);
});

Route::apiResource('trips', TripController::class)->middleware('auth:api');