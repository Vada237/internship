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
            'organization_id' => $params['organizationId']
        ]);

        $user->projects()->attach($project->id,['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        return $project;
    }
}
