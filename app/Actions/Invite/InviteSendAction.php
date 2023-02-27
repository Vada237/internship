<?php

namespace App\Actions\Invite;

use App\Mail\OrganizationInvite;
use App\Models\Invite;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InviteSendAction {
    public function handle($credentials,User $sender) {

        $token = Str::random(60);

        $invite = Invite::Create([
            'email' => $credentials['email'],
            'organization_id' => $credentials['organizationId'],
            'token' => $token
        ]);

        $organization = Organization::find($credentials['organizationId']);
        Mail::to($credentials['email'])->send(new OrganizationInvite($invite,$organization->name,$sender->name));

        return __('messages.mail.send.success');
    }
}
