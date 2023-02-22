<?php

namespace App\Http\Controllers\Api;

use App\Actions\Invite\InviteAcceptAction;
use App\Actions\Invite\InviteSendAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invite\InviteRequest;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function send (InviteSendAction $action,InviteRequest $request) {
        return $action->handle($request->validated(),Auth::user());
    }

    public function accept(InviteAcceptAction $action, string $token) {
        dd($token);
        return $action->handle($token);
    }
}
