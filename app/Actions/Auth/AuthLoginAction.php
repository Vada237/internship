<?php

namespace App\Actions\Auth;

use App\Http\Requests\Auth\AuthLoginRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class AuthLoginAction {
    public function handle($credentials) {

            if (!Auth::validate($credentials)) {
                return new AuthenticationException();
            }

            $user = Auth::getProvider()->retrieveByCredentials($credentials);

            return [
                "result" => __('Авторизация прошла успешно'),
                "id" => $user->id,
                "token" => $user->createtoken('token')->plainTextToken
            ];
        }
    }
