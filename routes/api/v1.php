<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::prefix('auth')->group(function() {
    Route::post('login', [App\Http\Controllers\User\AuthController::class, 'login'])
        ->name('auth.login');
    Route::middleware('can:is_admin')->post('register', [App\Http\Controllers\User\AuthController::class, 'register'])
        ->name('auth.register');
});

Route::middleware('auth:sanctum')->prefix('tours')->group(function() {

});

Route::middleware('auth:sanctum')->prefix('travel')->group(function() {
    Route::middleware('can:is_admin')->post('/', [App\Http\Controllers\TravelController::class, 'store'])
        ->name('travel.store');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
