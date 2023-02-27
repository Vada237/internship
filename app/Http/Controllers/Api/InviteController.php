<?php

namespace App\Http\Controllers\Api;

use App\Actions\Invite\InviteAcceptAction;
use App\Actions\Invite\InviteSendAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invite\InviteAcceptRequest;
use App\Http\Requests\Invite\InviteSendRequest;
use App\Models\Invite;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function send (InviteSendAction $action, InviteSendRequest $request) {
        $this->authorize('send', [Invite::class , Organization::find($request->organizationId)]);
        return $action->handle($request->validated(),Auth::user());
    }

    public function accept(InviteAcceptAction $action,string $token) {
        $this->authorize('accept', Invite::where('token', $token)->first());
        return $action->handle($token);
    }
}
