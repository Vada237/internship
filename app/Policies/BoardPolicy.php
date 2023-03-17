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
        return ($user->hasRole(Role::ADMIN));
    }

    public function viewByProject(User $user, Project $project)
    {
        return ($user->hasAnyOrganizationRole(Organization::find($project->organization_id),
                Role::ADMIN, Role::ORGANIZATION_SUPERVISOR) ||
            $user->hasAnyProjectRole($project, Role::PROJECT_SUPERVISOR));
    }

    public function view(User $user, Project $project)
    {
        return $user->hasAnyOrganizationRole(Organization::find($project->organization_id),
                Role::ORGANIZATION_SUPERVISOR, Role::ADMIN) ||
            $user->hasAnyProjectRole($project, Role::PROJECT_SUPERVISOR,
                Role::PROJECT_EXECUTOR, Role::PROJECT_PARTICIPANT);
    }

    public function create(User $user, Project $project)
    {
        return ($user->hasAnyOrganizationRole(Organization::find($project->organization_id),
                Role::ORGANIZATION_SUPERVISOR, Role::ADMIN) ||
            $user->hasAnyProjectRole($project, Role::PROJECT_SUPERVISOR));
    }

    public function update(User $user, Board $board)
    {
        return ($user->hasAnyOrganizationRole(Organization::find($board->project()->first()->organization_id),
                Role::ADMIN, Role::ORGANIZATION_SUPERVISOR) ||
            $user->hasAnyProjectRole(Project::find($board->project_id),
                Role::PROJECT_SUPERVISOR) && $board->status == Board::EDITED);
    }

    public function delete(User $user, Board $board)
    {
        return ($user->hasAnyOrganizationRole(Organization::find($board->project()->first()->organization_id),
                Role::ADMIN, Role::ORGANIZATION_SUPERVISOR) ||
            $user->hasAnyProjectRole(Project::find($board->project_id), Role::PROJECT_SUPERVISOR));
    }
}
