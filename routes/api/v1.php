<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function() {
    Route::post('login', [App\Http\Controllers\User\AuthController::class, 'login'])
        ->name('auth.login');
    Route::middleware('can:is_admin')->post('register', [App\Http\Controllers\User\AuthController::class, 'register'])
        ->name('auth.register');
});

Route::prefix('travels')->group(function() {
    Route::get('/', [App\Http\Controllers\TravelController::class, 'index'])
        ->name('travel.index');
    Route::get('/{slug}/tours', [App\Http\Controllers\TourController::class, 'tours'])
        ->name('travel.tours');
    Route::middleware(['auth:sanctum', 'can:is_admin'])->post('/', [App\Http\Controllers\TravelController::class, 'store'])
        ->name('travel.store');
    Route::middleware(['auth:sanctum', 'can:is_editor'])->put('/{travel}', [App\Http\Controllers\TravelController::class, 'update'])
        ->name('travel.update');
    Route::middleware(['auth:sanctum', 'can:is_admin'])->post('/{travel}/tours', [App\Http\Controllers\TourController::class, 'store'])
        ->name('travel.tour.store');
});
