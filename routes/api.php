<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api;
use App\Models;
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

Route::controller(Api\UserController::class)->group(function() {
    Route::middleware('auth:sanctum')->get('user', [Api\UserController::class, 'index']);
    Route::middleware('auth:sanctum')->get('user/{id}', [Api\UserController::class, 'show']);
    Route::middleware('auth:sanctum')->delete('user/{id}', [Api\UserController::class, 'destroy']);
});

Route::controller(Api\AuthController::class)->group(function() {
   Route::post('auth/login', [Api\AuthController::class, 'login']);
   Route::post('auth/register', [Api\AuthController::class, 'register']);
});

Route::controller(Api\PasswordController::class)->group(function () {
   Route::post('password/forgot', [Api\PasswordController::class, 'forgot'])->middleware('guest')->name('password.email');
   Route::post('password/reset', [Api\PasswordController::class, 'reset'])->middleware('guest')->name('password.reset');
});
