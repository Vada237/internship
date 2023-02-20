<?php

use App\Models\User;
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

Route::controller(Api\UserController::class)->middleware(['auth:sanctum'])->group(function() {
    Route::get('users/', [Api\UserController::class, 'index'])->can('viewAny',User::class);
    Route::get('users/{id}', [Api\UserController::class, 'show'])->can('view',User::class);
    Route::patch('users/{id}', [Api\UserController::class, 'update']);
    Route::delete('users/{id}', [Api\UserController::class, 'destroy']);
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
   Route::get('organizations/', [Api\OrganizationController::class, 'index'])->can('viewAny',User::class);
   Route::get('organizations/{id}', [Api\OrganizationController::class, 'show'])->can('view',User::class);
   Route::post('organizations', [Api\OrganizationController::class, 'store'])->can('create',User::class);
   Route::patch('organizations/{id}', [Api\OrganizationController::class, 'update']);
   Route::delete('organizations/{id}', [Api\OrganizationController::class, 'destroy']);
});
