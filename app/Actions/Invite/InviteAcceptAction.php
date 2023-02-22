<?php

namespace App\Actions\Invite;

use App\Models\Invite;
use App\Models\Role;
use App\Models\User;


class InviteAcceptAction {
    public function handle(string $token) {
        $invite = Invite::where('token', $token)->firstOrFail();
        $user = User::where('email', $token->email)->firstOrFail();
        $user->organizations()->attach($invite->organization_id, ['role_id', Role::where('slug', 'employee')->first()]);
        $invite->delete();
        return __('messages.organizations.join.success');
    }
}
