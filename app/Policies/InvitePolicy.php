<?php

namespace App\Policies;

use App\Models\Invite;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function send(User $user,Organization $organization)
    {
        return ($user->hasAnyRole('admin','organization-supervisor','employee')
            && ($user->organizations()->find($organization) != null));
    }

    public function update(User $user, Invite $invite)
    {
        //
    }

    public function delete(User $user, Invite $invite)
    {
        //
    }

    public function restore(User $user, Invite $invite)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Invite $invite)
    {
        //
    }
}
