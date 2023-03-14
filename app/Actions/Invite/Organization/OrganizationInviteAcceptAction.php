<?php

namespace App\Actions\Invite\Organization;

use App\Models\Invite;
use App\Models\Role;
use App\Models\User;


class OrganizationInviteAcceptAction
{
    public function handle(string $token)
    {
        $invite = Invite::where('token', $token)->firstOrFail();
        $user = User::findOrFail($invite->user_id);
        $user->organizations()->attach($invite->invitable_id,
            ['role_id' => Role::byName(Role::list['EMPLOYEE'])->first()->id]);

        $invite->delete();
        return __('messages.organizations.join.success');
    }
}
