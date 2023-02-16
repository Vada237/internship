<?php

namespace App\Actions\Auth;

use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthRegisterAction {
    public function handle(AuthRegisterRequest $request) {

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if ($request->hasFile('avatar')) {
            $user->avatar = $request->file('avatar')->store('public/avatars');
        }

        User::create($user->getAttributes());

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar
        ],201);
    }
}
