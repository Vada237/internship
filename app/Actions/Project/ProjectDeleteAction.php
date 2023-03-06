<?php

namespace App\Actions\Project;

use App\Models\Project;

class ProjectDeleteAction
{
    public function handle(Project $project)
    {
        $project->delete();
        return __('messages.projects.delete.success');
    }
}
