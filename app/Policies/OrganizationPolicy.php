<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        if ($user->hasRole('admin')) return true;
        return false;
    }
    public function update(User $user, Organization $organization)
    {
        return (($user->hasAnyRole('organization-supervisor') && $user->organizations()->get()->contains($organization)) || $user->hasAnyRole('admin'));
    }
    public function delete(User $user, Organization $organization)
    {
        return ($user->hasAnyRole('organization-supervisor') && $user->organizations()->get()->contains($organization) || $user->hasAnyRole('admin'));
    }
}
