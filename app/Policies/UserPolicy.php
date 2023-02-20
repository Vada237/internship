<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class UserPolicy
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

    public function update(User $user, User $editedUser)
    {
        return ($user->hasAnyRole('admin', 'user', 'organization-supervisor') && $user->id == $editedUser->id) || $user->hasRole('admin');
    }

    public function delete(User $user, User $deletedUser)
    {
        return ($user->hasAnyRole('admin', 'user', 'organization-supervisor') && $user->id == $deletedUser->id) || $user->hasRole('admin');
    }
}
