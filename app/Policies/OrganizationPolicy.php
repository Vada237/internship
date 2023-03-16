<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Config;

class OrganizationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return ($user->hasRole(Role::list['ADMIN']));

    }

    public function view(User $user, Organization $organization)
    {
        return ($user->organizations()->find($organization->id) != null || $user->hasRole(Role::list['ADMIN']));
    }

    public function update(User $user, Organization $organization)
    {
        return (($user->hasAnyOrganizationRole($organization, Role::list['ORGANIZATION_SUPERVISOR'])
            || $user->hasRole(Role::list['ADMIN'])));
    }

    public function delete(User $user, Organization $organization)
    {
        return ($user->hasAnyOrganizationRole($organization, Role::list['ORGANIZATION_SUPERVISOR'])
            || $user->hasRole(Role::list['ADMIN']));
    }
}
