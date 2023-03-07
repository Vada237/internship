<?php

namespace App\Actions\Invite\Project;

use App\Mail\ProjectInvite;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProjectInviteSendAction
{
    public function handle($params, User $sender)
    {
        $token = Str::random(60);

        $project = Project::find($params['projectId']);

        $project->invites()->create([
            'email' => $params['email'],
            'token' => $token
        ]);

        $invite = $project->invites()->where('invitable_type', 'project')
            ->where('email', $params['email'])->first();

        Mail::to($params['email'])->send(new ProjectInvite($invite, $sender->name));
        return __('messages.mail.send.success');
    }
}