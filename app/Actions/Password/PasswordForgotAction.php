<?php

namespace App\Actions\Password;

use App\Http\Requests\Password\PasswordForgotRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordForgotAction {
    public function handle(PasswordForgotRequest $request) {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        }
    }
}
