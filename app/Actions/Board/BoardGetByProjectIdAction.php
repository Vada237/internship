<?php

namespace App\Actions\Board;

use App\Models\Project;

class BoardGetByProjectIdAction
{
    public function handle(Project $project)
    {
        return $project->boards()->get();
    }
}
