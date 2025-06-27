<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\RideController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/cars', [CarController::class, 'index']);
    Route::post('/cars', [CarController::class, 'store']);
    Route::get('/cars/{car_id}', [CarController::class, 'show']);
    Route::patch('/cars/{car_id}', [CarController::class, 'update']);
    Route::delete('/cars/{car_id}', [CarController::class, 'delete']);

    Route::get('/rides', [RideController::class, 'index']);
    Route::post('/rides', [RideController::class, 'store']);
    Route::get('/rides/{ride_id}', [RideController::class, 'show']);
    Route::patch('/rides/{ride_id}', [RideController::class, 'update']);
    Route::delete('/rides/{ride_id}', [RideController::class, 'delete']);
});
