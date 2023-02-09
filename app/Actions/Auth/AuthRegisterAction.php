<?php

namespace App\Actions\Auth;

use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Psy\Util\Json;

class AuthRegisterAction {
    public function handle(AuthRegisterRequest $request) {

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

        return [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar
        ];
    }
}
