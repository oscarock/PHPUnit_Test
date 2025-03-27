<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ReservationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('resources', [ResourceController::class, 'index']);
Route::get('resources/{id}/availability', [ResourceController::class, 'availability']);
Route::resource('reservations', ReservationController::class)->only(['store', 'destroy']);
