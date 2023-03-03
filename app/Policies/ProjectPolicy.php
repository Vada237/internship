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
        return $user->hasRole(Role::list['ADMIN']);
    }

    public function view(User $user, Project $project): bool
    {
        return ($user->hasRole(Role::list['ADMIN']) || $user->organizations()->find($project->organization_id));
    }

    public function viewByOrganization(User $user, Organization $organization): bool
    {
        return ($user->hasRole(Role::list['ADMIN']) || $user->organizations()->find($organization));
    }

    public function create(User $user, Organization $organization): bool
    {
        return ($user->hasRole(Role::list['ADMIN']) ||
            ($user->hasAnyOrganizationRole($organization, Role::list['ORGANIZATION_SUPERVISOR'], Role::list['EMPLOYEE'])
                && $user->organizations()->find($organization->id)));
    }

    public function update(User $user, Project $project)
    {
        return ($user->hasRole(Role::list['ADMIN']) ||
            ($user->hasAnyProjectRole($project, Role::list['PROJECT_SUPERVISOR'], Role::list['PROJECT_EXECUTOR'])));
    }

    public function delete(User $user, Project $project)
    {
        return ($user->hasRole(Role::list['ADMIN']) ||
            $user->hasRole(Role::list['ORGANIZATION_SUPERVISOR']) ||
            $user->hasAnyProjectRole($project, Role::list['PROJECT_SUPERVISOR']));
    }

    public function restore(User $user, Project $project)
    {
        //
    }

    public function forceDelete(User $user, Project $project)
    {
        //
    }
}
