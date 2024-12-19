<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReservationController;
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
Route::get('/reservations', [ReservationController::class, 'index']);
Route::middleware('auth:api')->get('/reservations', [ReservationController::class, 'index']);
Route::get('/reservations', [ReservationController::class, 'apiIndex'])->name('reservations.api');
Route::delete('/reservations/{id}', [ReservationController::class, 'deleteReservation'])->name('reservations.delete');
Route::post('/reservations', [ReservationController::class, 'store']);
Route::put('/reservations/{id}', [ReservationController::class, 'updateReservation']);
Route::middleware(['api'])->put('/reservations/{id}', [ReservationController::class, 'updateReservation']);
Route::put('/reservations/calendar/{id}', [ReservationController::class, 'updateCalendar']);
Route::post('/reservations/cancel/{id}', [ReservationController::class, 'processReservationCancellation']);

