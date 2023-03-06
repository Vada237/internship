<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\AuthLoginAction;
use App\Actions\Auth\AuthRegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register (AuthRegisterAction $action,AuthRegisterRequest $request) {
        return $action->handle($request);
    }

    public function login(AuthLoginAction $action,AuthLoginRequest $request) {
        return $action->handle($request);
    }
}
