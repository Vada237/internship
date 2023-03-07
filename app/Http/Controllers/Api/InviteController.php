<?php

namespace App\Http\Controllers\Api;

use App\Actions\Invite\Organization\OrganizationInviteAcceptAction;
use App\Actions\Invite\Organization\OrganizationInviteSendAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invite\InviteAcceptRequest;
use App\Http\Requests\Invite\InviteSendRequest;
use App\Models\Invite;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function send(OrganizationInviteSendAction $action, InviteSendRequest $request)
    {
        $this->authorize('send', [Invite::class, Organization::find($request->organizationId)]);
        return $action->handle($request->validated(), Auth::user());
    }

    public function accept(OrganizationInviteAcceptAction $action, string $token)
    {
        $this->authorize('accept', Invite::where('token', $token)->first());
        return $action->handle($token);
    }
}
