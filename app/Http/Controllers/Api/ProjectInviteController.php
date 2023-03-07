<?php

namespace app\Http\Controllers\Api;

use App\Actions\Invite\Project\ProjectInviteAcceptAction;
use App\Actions\Invite\Project\ProjectInviteSendAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invite\InviteProjectSendRequest;
use App\Models\Invite;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectInviteController extends Controller
{
    public function send(ProjectInviteSendAction $action, InviteProjectSendRequest $request)
    {
        $this->authorize('sendProject', [Invite::class, Project::find($request->projectId)]);
        return $action->handle($request->validated(),Auth::user());
    }

    public function accept(ProjectInviteAcceptAction $action, string $token) {
        $this->authorize('accept', [Invite::class, Invite::where('token', $token)->first()]);
        return $action->handle($token);
    }
}
