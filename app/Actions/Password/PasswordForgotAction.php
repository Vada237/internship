<?php

namespace App\Actions\Password;

use App\Http\Requests\Password\PasswordForgotRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordForgotAction {
    public function handle($credentials) {
        $status = Password::sendResetLink(
            $credentials->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        }
    }
}
