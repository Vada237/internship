<?php

namespace App\Actions\Project;

use App\Models\Organization;
use App\Models\Project;

class ProjectGetByOrganizationAction
{
    public function handle(Organization $organization)
    {
        return $organization->projects()->get();
    }
}
