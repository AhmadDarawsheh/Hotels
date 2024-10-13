<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/hello', function () {
    return response()->json(['message' => 'User registered successfully!']);
});



Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

Route::middleware('auth:api', 'role:employee')->group(function () {
    Route::post('/hotels/createHotel', [AdminController::class, 'createhotel']);
    Route::post('/hotels/deleteHotel/{hotel}', [AdminController::class, 'deleteHotel']);
    Route::post('/hotels/addEmployeeToHotel/{hotel}', [AdminController::class, 'addEmployeeToHotel']);
    Route::post('/hotels/deleteEmployeeFromHotel/{hotel}', [AdminController::class, 'deleteEmployeeFromHotel']);
    Route::post('/hotels/{hotel}/createRoom', [RoomController::class, 'addRoom']);
    Route::post('/hotels/{hotel}/deleteRoom/{room}', [RoomController::class, 'removeRoom']);
    Route::post('/hotels/rooms/{room}/createRating', [RatingController::class, 'addRating']);
    Route::post('/hotels/rooms/{room}/removeRating/{rating}', [RatingController::class, 'removeRating']);
});


Route::middleware('auth:api', 'role:customer')->group(function () {
    Route::post('/hotels/{hotel}/rooms/{room}/createReservation', [ReservationController::class, 'createReservation']);
    Route::get('/getReservations',[ReservationController::class, 'getReservations']);
});

Route::get('/hotels', [AdminController::class, 'searchHotels']);
Route::get('/hotels/{hotel}/rooms', [RoomController::class, 'getRooms']);
Route::get('/hotels/rooms/{room}/ratings', [RatingController::class, 'getRatings']);
