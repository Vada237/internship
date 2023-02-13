<?php

namespace App\Http\Controllers\Api;

use App\Actions\Password\PasswordForgotAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Password\PasswordForgotRequest;
use App\Http\Requests\Password\PasswordResetRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function forgot (PasswordForgotAction $action,PasswordForgotRequest $request) {
        return $action->handle($request->all());
    }

    public function reset (PasswordForgotAction $action,PasswordResetRequest $request) {
        return $action->handle($request->all());
    }
}
