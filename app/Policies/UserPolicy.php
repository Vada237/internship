<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return ($user->hasRole(Role::list['ADMIN']));
    }

    public function update(User $user, User $editedUser)
    {
        return ($user->id == $editedUser->id || $user->hasRole(Role::list['ADMIN']));
    }

    public function delete(User $user, User $deletedUser)
    {
        return ($user->id == $deletedUser->id || $user->hasRole(Role::list['ADMIN']));
    }
}
