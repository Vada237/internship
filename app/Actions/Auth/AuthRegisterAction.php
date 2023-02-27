<?php

namespace App\Actions\Auth;

use App\Models\User;

class AuthRegisterAction {
    public function handle($credentials) {

        $user = User::Create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ]);

        if (array_key_exists('avatar', $credentials)) {
            $user->avatar = $credentials['avatar']->store('public/avatars');
        }

        return $user;
    }
}
