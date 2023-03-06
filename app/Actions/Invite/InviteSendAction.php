<?php

namespace App\Actions\Invite;

use App\Mail\OrganizationInvite;
use App\Models\Invite;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InviteSendAction
{
    public function handle($params, User $sender)
    {
        $token = Str::random(60);

        $invite = Invite::Create([
            'email' => $params['email'],
            'organization_id' => $params['organizationId'],
            'token' => $token
        ]);

        $organization = Organization::find($params['organizationId']);
        Mail::to($params['email'])->send(new OrganizationInvite($invite, $organization->name, $sender->name));

        return __('messages.mail.send.success');
    }
}
