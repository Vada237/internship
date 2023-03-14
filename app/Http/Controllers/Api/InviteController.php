<?php

namespace App\Http\Controllers\Api;

use App\Actions\Invite\Organization\OrganizationInviteAcceptAction;
use App\Actions\Invite\Organization\OrganizationInviteSendAction;
use App\Actions\Invite\Project\ProjectInviteAcceptAction;
use App\Actions\Invite\Project\ProjectInviteSendAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invite\InviteAcceptRequest;
use App\Http\Requests\Invite\InviteProjectSendRequest;
use App\Http\Requests\Invite\InviteSendRequest;
use App\Models\Invite;
use App\Models\Organization;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function send(OrganizationInviteSendAction $action, InviteSendRequest $request)
    {
        $this->authorize('send', [Invite::class, Organization::find($request->validated('organization_id'))]);
        return $action->handle($request->validated(), Auth::user());
    }

    public function accept(OrganizationInviteAcceptAction $action, string $token)
    {
        $this->authorize('accept', Invite::where('token', $token)->firstOrFail());
        return $action->handle($token);
    }

    public function sendProject(ProjectInviteSendAction $action, InviteProjectSendRequest $request)
    {
        $this->authorize('sendProject', [Invite::class, Project::find($request->validated('project_id'))]);
        return $action->handle($request->validated(), Auth::user());
    }

    public function acceptProject(ProjectInviteAcceptAction $action, string $token)
    {
        $this->authorize('accept', [Invite::class, Invite::where('token', $token)->firstOrFail()]);
        return $action->handle($token);
    }
}
