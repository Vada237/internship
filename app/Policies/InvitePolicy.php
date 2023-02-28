<?php

namespace App\Policies;

use App\Models\Invite;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Config;

class InvitePolicy
{
    use HandlesAuthorization;

    public function send(User $user, Organization $organization)
    {
        return ($user->hasAnyRole(
                Role::list['EMPLOYEE'],
                Role::list['ORGANIZATION_SUPERVISOR'],
                Role::list['ADMIN']
            ) && ($user->organizations()->find($organization) != null));
    }

    public function accept(User $user, Invite $invite)
    {
        return $user->email == $invite->email;
    }
}
