<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function register (Request $request) {

        $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|min:6|email',
            'password' => 'required|min:6',
            'avatar' => 'image|mimes:jpeg,bmp,png'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if ($request->hasFile('avatar')) {
            $user->avatar = $request->file('avatar')->store('public/avatars');
        }

        User::create($user->getAttributes());

        return response()->json(([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'avatar' => $user->avatar
        ]));
    }

    public function login(Request $request) {

        $request->validate([
           'email' => 'required|min:6|email',
           'password' => 'required|min:6'
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response('Неверный логин или пароль');
        }
        $user = User::where('email',$request->email)->first();
            return response([
                "result" => 'Авторизация прошла успешно',
                "name" => $user->name,
                "token" => $user->createtoken('token')->plainTextToken
            ]);
    }
}
