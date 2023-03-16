<?php

use App\Models\Organization;
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

Route::controller(Api\UserController::class)
    ->prefix('users')->middleware(['auth:sanctum'])->group(function () {
    Route::get('', [Api\UserController::class, 'index']);
    Route::get('{user}', [Api\UserController::class, 'show']);
    Route::patch('{user}', [Api\UserController::class, 'update']);
    Route::delete('{user}', [Api\UserController::class, 'destroy']);
});

Route::controller(Api\AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', [Api\AuthController::class, 'login']);
    Route::post('register', [Api\AuthController::class, 'register']);
});

Route::controller(Api\PasswordController::class)->prefix('password')
    ->middleware('guest')->group(function () {
    Route::post('forgot', [Api\PasswordController::class, 'forgot'])->name('password.email');
    Route::post('reset', [Api\PasswordController::class, 'reset'])->name('password.reset');
});

Route::controller(Api\OrganizationController::class)->prefix('organizations')
    ->middleware('auth:sanctum')->group(function () {
    Route::get('', [Api\OrganizationController::class, 'index']);
    Route::get('{organization}', [Api\OrganizationController::class, 'show']);
    Route::post('', [Api\OrganizationController::class, 'store']);
    Route::patch('{organization}', [Api\OrganizationController::class, 'update']);
    Route::delete('{organization}', [Api\OrganizationController::class, 'destroy']);
});

Route::controller(Api\InviteController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('invites/send', [Api\InviteController::class, 'send']);
    Route::get('invites/accept/{token}', [Api\InviteController::class, 'accept'])->name('accept');
});

Route::controller(Api\ProjectController::class)->prefix('projects')
    ->middleware('auth:sanctum')->group(function () {
    Route::get('', [Api\ProjectController::class, 'index']);
    Route::get('find-by-organization/{organization}', [Api\ProjectController::class, 'getByOrganization']);
    Route::get('{project}', [Api\ProjectController::class, 'show']);
    Route::post('', [Api\ProjectController::class, 'store']);
    Route::patch('{project}', [Api\ProjectController::class, 'update']);
    Route::delete('{project}', [Api\ProjectController::class, 'destroy']);
});

Route::controller(Api\InviteController::class)->prefix('invites')
    ->middleware('auth:sanctum')->group(function () {
    Route::post('send/organization', [Api\InviteController::class, 'send']);
    Route::get('accept/organization/{token}', [Api\InviteController::class, 'accept'])->name('organization.accept');
    Route::post('send/project', [Api\InviteController::class, 'sendProject']);
    Route::get('accept/project/{token}', [Api\InviteController::class, 'acceptProject'])->name('project.accept');
});

Route::controller(Api\BoardTemplateController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('board-templates', [Api\BoardTemplateController::class, 'store']);
    Route::get('board-templates', [Api\BoardTemplateController::class, 'index']);
    Route::get('board-templates/{boardTemplate}', [Api\BoardTemplateController::class, 'show']);
    Route::patch('board-templates/{boardTemplate}', [Api\BoardTemplateController::class, 'update']);
    Route::delete('board-templates/{boardTemplate}', [Api\BoardTemplateController::class, 'destroy']);
});

Route::controller(Api\TaskTemplateController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('task-templates', [Api\TaskTemplateController::class, 'store']);
    Route::get('task-templates', [Api\TaskTemplateController::class, 'index']);
    Route::get('task-templates/{taskTemplate}', [Api\TaskTemplateController::class, 'show']);
    Route::patch('task-templates/{taskTemplate}', [Api\TaskTemplateController::class, 'update']);
    Route::delete('task-templates/{taskTemplate}', [Api\TaskTemplateController::class, 'destroy']);
});

Route::controller(Api\SubtaskTemplateController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('subtask-templates', [Api\SubtaskTemplateController::class, 'store']);
    Route::put('subtask-templates/{subtaskTemplate}', [Api\SubtaskTemplateController::class, 'update']);
    Route::get('subtask-templates/{subtaskTemplate}', [Api\SubtaskTemplateController::class, 'show']);
    Route::delete('subtask-templates/{subtaskTemplate}', [Api\SubtaskTemplateController::class, 'destroy']);
    Route::post('subtask-templates/{subtaskTemplate}/attributes', [Api\SubtaskTemplateController::class, 'addAttribute']);
    Route::put('subtask-templates/{subtaskTemplate}/attributes/{attribute}', [Api\SubtaskTemplateController::class, 'updateAttribute']);
    Route::delete('subtask-templates/{subtaskTemplate}/attributes/{attribute}', [Api\SubtaskTemplateController::class, 'deleteAttribute']);
});

Route::controller(Api\BoardController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('boards', [Api\BoardController::class, 'store']);
    Route::get('boards', [Api\BoardController::class, 'index']);
    Route::get('boards/find-by-project/{project}', [Api\BoardController::class, 'findByProject']);
    Route::get('boards/{board}', [Api\BoardController::class, 'show']);
    Route::put('boards/{board}', [Api\BoardController::class, 'update']);
    Route::delete('boards/{board}', [Api\BoardController::class, 'destroy']);
});
