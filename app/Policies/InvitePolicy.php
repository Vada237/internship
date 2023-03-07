<?php

namespace App\Policies;

use App\Models\Invite;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Config;
use function Symfony\Component\String\u;

class InvitePolicy
{
    use HandlesAuthorization;

    public function send(User $user, Organization $organization)
    {
        return ($user->hasAnyOrganizationRole($organization,
                Role::list['EMPLOYEE'],
                Role::list['ORGANIZATION_SUPERVISOR'],
                Role::list['ADMIN']
            ) && ($user->organizations()->find($organization) != null));
    }

    public function accept(User $user, Invite $invite)
    {
        return $user->email == $invite->email;
    }

    public function sendProject(User $user, Project $project)
    {
        return $user->hasAnyOrganizationRole(Organization::find($project->organization_id), Role::list['ADMIN'], Role::list['ORGANIZATION_SUPERVISOR']) ||
            $user->hasAnyProjectRole($project, Role::list['PROJECT_SUPERVISOR'], Role::list['PROJECT_EXECUTOR']);
    }
}
