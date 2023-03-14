<?php

namespace App\Actions\Invite\Project;

use App\Models\Invite;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;


class ProjectInviteAcceptAction
{
    public function handle(string $token)
    {
        $invite = Invite::where('token', $token)->first();
        $user = User::where('email', $invite->email)->first();
        $project = Project::find($invite->invitable_id);

        if ($user->organizations()->find($project->organization_id) == null) {
            $user->organizations()->attach($project->organization_id,
                ['role_id' => Role::byName(Role::list['USER'])->first()->id]);
        }

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);

        $invite->delete();

        return __('messages.projects.join.success');
    }
}
