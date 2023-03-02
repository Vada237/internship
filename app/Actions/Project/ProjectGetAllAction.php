<?php

namespace App\Actions\Project;

use App\Models\Project;

class ProjectGetAllAction
{
    public function handle($params)
    {
        return Project::limit($params['limit'])->offset($params['offset'])->get();
    }
}
