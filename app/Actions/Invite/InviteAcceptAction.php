<?php

namespace App\Actions\Invite;

use App\Models\Invite;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Config;


class InviteAcceptAction
{
    public function handle(string $token)
    {
        $invite = Invite::where('token', $token)->firstOrFail();
        $user = User::byEmail($invite->email)->firstOrFail();
        $user->organizations()->attach($invite->organization_id, ['role_id' => Role::byName(Role::list['EMPLOYEE'])->first()->id]);
        $invite->delete();
        return __('messages.organizations.join.success');
    }
}
