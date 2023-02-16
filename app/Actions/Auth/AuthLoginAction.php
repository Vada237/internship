<?php

namespace App\Actions\Auth;

use App\Http\Requests\Auth\AuthLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;

class AuthLoginAction {
    public function handle(AuthLoginRequest $request) {

            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return response('Неверный логин или пароль');
            }
            $user = User::where('email', $request->email)->first();
            return [
                "result" => 'Авторизация прошла успешно',
                "id" => $user->id,
                "token" => $user->createtoken('token')->plainTextToken
            ];
        }
    }
