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
        return $user->hasAnyRole('admin', 'user', 'organization-supervisor');
    }
    public function view(User $user)
    {
        return $user->hasAnyRole('admin', 'user', 'organization-supervisor');
    }
    public function create(User $user)
    {
        return $user->hasAnyRole('admin', 'user', 'organization-supervisor');
    }
    public function update(User $user, Organization $organization)
    {
        return ($user->hasAnyRole('admin', 'organization-supervisor'));
    }
    public function delete(User $user, Organization $organization)
    {
        return $user->hasAnyRole('admin', 'organization-supervisor');
    }
    public function restore(User $user, Organization $organization)
    {
        //
    }
    public function forceDelete(User $user, Organization $organization)
    {
        //
    }
}
