<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole(Role::ADMIN);
    }

    public function view(User $user, Project $project): bool
    {
        return ($user->hasRole(Role::ADMIN) || $user->organizations()->find($project->organization_id));
    }

    public function viewByOrganization(User $user, Organization $organization): bool
    {
        return ($user->hasRole(Role::ADMIN) || $user->organizations()->find($organization));
    }

    public function create(User $user, Organization $organization): bool
    {
        return ($user->hasRole(Role::ADMIN) ||
            ($user->hasAnyOrganizationRole($organization, Role::ORGANIZATION_SUPERVISOR, Role::EMPLOYEE)
                && $user->organizations()->find($organization->id)));
    }

    public function update(User $user, Project $project)
    {
        return ($user->hasRole(Role::ADMIN) ||
            ($user->hasAnyProjectRole($project, Role::PROJECT_SUPERVISOR, Role::PROJECT_EXECUTOR)));
    }

    public function delete(User $user, Project $project)
    {
        return ($user->hasAnyOrganizationRole(Organization::find($project->organization_id),
                Role::ADMIN, Role::ORGANIZATION_SUPERVISOR)
            || $user->hasAnyProjectRole($project, Role::PROJECT_SUPERVISOR));
    }
}
