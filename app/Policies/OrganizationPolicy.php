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
        if ($user->hasRole(Role::list['ADMIN'])) return true;
        return false;
    }

    public function view(User $user,Organization $organization) {
        if ($user->organizations()->find($organization->id) != null || $user->hasRole(Role::list['ADMIN'])) return true;
        return false;
    }
    public function update(User $user, Organization $organization)
    {
        return (($user->hasAnyRole(Role::list['ORGANIZATION_SUPERVISOR']) && $user->organizations()->get()->contains($organization))
            || $user->hasAnyRole(Role::list['ADMIN']));
    }
    public function delete(User $user, Organization $organization)
    {
        return ($user->hasAnyRole(Role::list['ORGANIZATION_SUPERVISOR']) && $user->organizations()->get()->contains($organization)
            || $user->hasAnyRole(Role::list['ADMIN']));
    }
}
