<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\AuthLoginAction;
use App\Actions\Auth\AuthRegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Requests\AuthRequest;

class AuthController extends Controller
{
    public function register (AuthRegisterAction $action,AuthRegisterRequest $request) {
        return $action->handle($request->all());
    }

    public function login(AuthLoginAction $action,AuthLoginRequest $request) {
        return $action->handle($request->only('email','password'));
    }
}
