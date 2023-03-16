<?php

namespace App\Actions\Project;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;

class ProjectCreateAction
{
    public function handle($params, User $user)
    {
        $project = Project::create([
            'name' => $params['name'],
            'organization_id' => $params['organization_id']
        ]);

        $user->projects()->attach($project->id, ['role_id' => Role::byName(Role::PROJECT_SUPERVISOR)->first()->id]);

        return $project;
    }
}
