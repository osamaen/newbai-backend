<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\BedSpaceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\ReservationTypeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('login',[UserController::class,'login']);





Route::middleware('auth:sanctum')->group(function(){
   
    Route::resource('buildings', BuildingController::class);
    Route::resource('customers',CustomerController::class);
    Route::resource('reservations',ReservationController::class);
    Route::resource('apartments',ApartmentController::class);
    Route::resource('rooms',RoomController::class);
    Route::resource('room-types',RoomTypeController::class);
    Route::resource('reservation-types',ReservationTypeController::class);
    Route::get('buildings/{id}/apartments', [ApartmentController::class, 'getApartments']);
    Route::get('rooms/{id}/bedspaces', [RoomController::class, 'getBedSpaces']);
    Route::post('/rooms/availability', [RoomController::class, 'checkAvailability']);
   
    // Route::post('add-doctor',[DoctorController::class,'store']);
    // Route::put('update-doctor/{id}',[DoctorController::class,'update']);

 

});