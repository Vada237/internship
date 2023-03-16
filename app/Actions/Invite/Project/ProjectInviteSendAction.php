<?php

namespace App\Actions\Invite\Project;

use App\Mail\ProjectInvite;
use App\Models\Invite;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProjectInviteSendAction
{
    public function handle($params, User $sender)
    {
        $token = Str::random(60);

        $project = Project::find($params['project_id']);

        $project->invites()->create([
            'user_id' => $params['user_id'],
            'token' => $token
        ]);

        $invite = $project->invites()->where('invitable_type', Invite::PROJECT)
            ->where('user_id', $params['user_id'])->first();

        Mail::to(User::find($params['user_id'])->email)->send(new ProjectInvite($invite, $sender->name));
        return __('messages.mail.send.success');
    }
}
