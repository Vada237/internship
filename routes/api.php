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

Route::controller(Api\UserController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('users/', [Api\UserController::class, 'index']);
    Route::get('users/{id}', [Api\UserController::class, 'show']);
    Route::delete('users/{id}', [Api\UserController::class, 'destroy']);
    Route::patch('users/', [Api\UserController::class, 'update']);
});

Route::controller(Api\AuthController::class)->group(function() {
   Route::post('auth/login', [Api\AuthController::class, 'login']);
   Route::post('auth/register', [Api\AuthController::class, 'register']);
});

Route::controller(Api\PasswordController::class)->middleware('guest')->group(function () {
   Route::post('password/forgot', [Api\PasswordController::class, 'forgot'])->name('password.email');
   Route::post('password/reset', [Api\PasswordController::class, 'reset'])->name('password.reset');
});

Route::controller(Api\OrganizationController::class)->middleware('auth:sanctum')->group(function () {
   Route::get('organizations/', [Api\OrganizationController::class, 'index']);
   Route::get('organizations/{id}', [Api\OrganizationController::class, 'show']);
   Route::get('organizations/{name}', [Api\OrganizationController::class, 'getByName']);
   Route::post('organizations', [Api\OrganizationController::class, 'store']);
   Route::patch('organizations/{id}', [Api\OrganizationController::class, 'update']);
   Route::delete('organizations/{id}', [Api\OrganizationController::class, 'destroy']);
});
