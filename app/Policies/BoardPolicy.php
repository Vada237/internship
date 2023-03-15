<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BoardPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return ($user->hasRole(Role::list['ADMIN']));
    }

    public function viewByProject(User $user, Project $project)
    {
        return ($user->hasAnyOrganizationRole(Organization::find($project->organization_id),
                Role::list['ADMIN'], Role::list['ORGANIZATION_SUPERVISOR']) ||
            $user->hasAnyProjectRole($project, Role::list['PROJECT_SUPERVISOR']));
    }

    public function view(User $user, Project $project)
    {
        return $user->hasAnyOrganizationRole(Organization::find($project->organization_id),
                Role::list['ORGANIZATION_SUPERVISOR'], Role::list['ADMIN']) ||
            $user->hasAnyProjectRole($project, Role::list['PROJECT_SUPERVISOR'],
                Role::list['PROJECT_EXECUTOR'], Role::list['PROJECT_PARTICIPANT']);
    }

    public function create(User $user, Project $project)
    {
        return ($user->hasAnyOrganizationRole(Organization::find($project->organization_id),
                Role::list['ORGANIZATION_SUPERVISOR'], Role::list['ADMIN']) ||
            $user->hasAnyProjectRole($project, Role::list['PROJECT_SUPERVISOR']));
    }

    public function update(User $user, Board $board)
    {
        return ($user->hasAnyOrganizationRole(Organization::find($board->project()->first()->organization_id),
                Role::list['ADMIN'], Role::list['ORGANIZATION_SUPERVISOR']) ||
            $user->hasAnyProjectRole(Project::find($board->project_id),
                Role::list['PROJECT_SUPERVISOR']) && $board->status == Board::statuses['EDITED']);
    }

    public function delete(User $user, Board $board)
    {
        return ($user->hasAnyOrganizationRole(Organization::find($board->project()->first()->organization_id),
                Role::list['ADMIN'], Role::list['ORGANIZATION_SUPERVISOR']) ||
            $user->hasAnyProjectRole(Project::find($board->project_id), Role::list['PROJECT_SUPERVISOR']));
    }
}
