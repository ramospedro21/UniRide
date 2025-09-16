<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PassengerRideController;
use App\Http\Controllers\Api\RideController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/save-device-token', [NotificationController::class, 'store']);

    Route::get('/users/{user_id}', [UsersController::class, 'show']);
    Route::post('/users/{user_id}', [UsersController::class, 'update']);
    Route::delete('/users/{user_id}', [UsersController::class, 'delete']);

    Route::get('/cars', [CarController::class, 'index']);
    Route::post('/cars', [CarController::class, 'store']);
    Route::get('/cars/{car_id}', [CarController::class, 'show']);
    Route::patch('/cars/{car_id}', [CarController::class, 'update']);
    Route::delete('/cars/{car_id}', [CarController::class, 'delete']);
    Route::get('/getCarsByUser/{user_id}', [CarController::class, 'getCarsByUser']);

    Route::get('/rides', [RideController::class, 'index']);
    Route::post('/rides', [RideController::class, 'store']);
    Route::get('/rides/{ride_id}', [RideController::class, 'show']);
    Route::patch('/rides/{ride_id}', [RideController::class, 'update']);
    Route::delete('/rides/{ride_id}', [RideController::class, 'delete']);
    Route::get('/getRidesByUser/{user_id}', [RideController::class, 'getRidesByUser']);
    Route::get('/driver/{driverId}/ridesWithPendingRequests', [RideController::class, 'getRidesWithPendingRequests']);

    Route::get('/passengerRides', [PassengerRideController::class, 'index']);
    Route::post('/passengerRides', [PassengerRideController::class, 'store']);
    Route::get('/passengerRides/{passenger_ride_id}', [PassengerRideController::class, 'show']);
    Route::patch('/passengerRides/{passenger_ride_id}', [PassengerRideController::class, 'update']);
    Route::delete('/passengerRides/{passenger_ride_id}', [PassengerRideController::class, 'delete']);
    Route::post('/passengerRides/{passenger_ride_id}/handleReservation', [PassengerRideController::class, 'handleReservation']);

    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::post('/conversations', [ConversationController::class, 'store']);
    Route::get('/messages/{conversation}', [MessageController::class, 'show']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::post('messages/{conversation}/mark-as-read', [MessageController::class, 'markAsRead']);

});
