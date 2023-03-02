<?php

namespace App\Actions\Project;

use App\Models\Project;

class ProjectGetByIdAction {
    public function handle(Project $project)
    {
        return $project;
    }
}
