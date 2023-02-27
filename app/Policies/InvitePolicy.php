<?php

namespace App\Policies;

use App\Models\Invite;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitePolicy
{
    use HandlesAuthorization;

    public function send (User $user,Organization $organization) {
        return ($user->hasAnyRole('admin','organization-supervisor','employee')
            && ($user->organizations()->find($organization) != null));
    }

    public function accept(User $user, Invite $invite) {
        return $user->email == $invite->email;
    }
}
