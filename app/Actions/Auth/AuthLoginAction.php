<?php

namespace App\Actions\Auth;

use App\Http\Requests\Auth\AuthLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthLoginAction {
    public function handle(AuthLoginRequest $request) {

        $request->validate([
            'email' => 'required|min:6|email',
            'password' => 'required|min:6'
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response('Неверный логин или пароль');
        }
        $user = User::where('email',$request->email)->first();
        return [
            "result" => 'Авторизация прошла успешно',
            "name" => $user->name,
            "token" => $user->createtoken('token')->plainTextToken
        ];
    }
}
