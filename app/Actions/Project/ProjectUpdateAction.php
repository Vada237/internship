<?php

namespace App\Actions\Project;

use App\Models\Project;

class ProjectUpdateAction
{
    public function handle($params, Project $project)
    {
        $project->update($params);
        return $project;
    }
}
