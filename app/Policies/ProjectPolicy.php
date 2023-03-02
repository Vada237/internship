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

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(Role::list['ADMIN']);
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function view(User $user, Project $project): bool
    {
        return ($user->hasRole(Role::list['ADMIN']) || $user->organizations()->find($project->organization_id));
    }

    public function viewByOrganization(User $user, Organization $organization): bool
    {
        return ($user->hasRole(Role::list['ADMIN']) || $user->organizations()->find($organization));
    }

    /**
     * @param User $user
     * @param Organization $organization
     * @return bool
     */
    public function create(User $user, Organization $organization): bool
    {
        return ($user->hasRole(Role::list['ADMIN']) ||
            ($user->hasAnyRole(Role::list['ORGANIZATION_SUPERVISOR'], Role::list['EMPLOYEE']) && $user->organizations()->find($organization->id)));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Project $project)
    {

        return ($user->hasRole(Role::list['ADMIN']) ||
            ($user->hasAnyProjectRole(Role::list['PROJECT_SUPERVISOR'], Role::list['PROJECT_EXECUTOR']) &&
            $user->projects()->find($project->id)));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Project $project)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Project $project)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Project $project)
    {
        //
    }
}
