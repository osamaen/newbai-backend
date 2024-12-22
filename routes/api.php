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
use App\Http\Controllers\ReservationStatusController;
use App\Http\Controllers\NationalityController;
use App\Http\Controllers\LeadSourceController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Storage;
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
    Route::resource('users', UserController::class);
    Route::resource('buildings', BuildingController::class);
    Route::resource('customers',CustomerController::class);
    Route::get('image',[CustomerController::class,'getImage']);
    Route::POST('customers/search', [CustomerController::class, 'search']);
    Route::resource('reservations',ReservationController::class);
    Route::post('reservations/update-status', [ReservationController::class, 'updateStatus']);
    Route::resource('nationalities',NationalityController::class);
    Route::resource('apartments',ApartmentController::class);
    Route::resource('rooms',RoomController::class);
    Route::resource('room-types',RoomTypeController::class);
    Route::resource('reservation-types',ReservationTypeController::class);
    Route::post('today-check-in',[ReservationController::class, 'todayCheckIn']);
    Route::post('today-check-out',[ReservationController::class, 'todayCheckOut']);
    Route::get('buildings/{id}/apartments', [ApartmentController::class, 'getApartments']);
    Route::get('rooms/{id}/bedspaces', [RoomController::class, 'getBedSpaces']);
    Route::post('/rooms/availability', [RoomController::class, 'checkAvailability']);
    Route::resource('reservation-statuses',ReservationStatusController::class);
    Route::resource('lead-sources',LeadSourceController::class);
    Route::resource('employees',EmployeeController::class);
    // Route::post('add-doctor',[DoctorController::class,'store']);
    // Route::put('update-doctor/{id}',[DoctorController::class,'update']);

    Route::post('/logout', [UserController::class, 'logout']);

});