<?php

namespace App\Http\Controllers\Api;

use App\Actions\Password\PasswordForgotAction;
use App\Actions\Password\PasswordResetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Password\PasswordForgotRequest;
use App\Http\Requests\Password\PasswordResetRequest;

class PasswordController extends Controller
{
    public function forgot(PasswordForgotAction $action, PasswordForgotRequest $request) {
        return $action->handle($request->validated());
    }

    public function reset(PasswordResetAction $action, PasswordResetRequest $request) {
        return $action->handle($request->validated());
    }
}
