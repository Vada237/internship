<?php

namespace App\Actions\Invite\Organization;

use App\Mail\OrganizationInvite;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrganizationInviteSendAction
{
    public function handle($params, User $sender)
    {
        $token = Str::random(60);

        $organization = Organization::find($params['organizationId']);

        $organization->invites()->create([
            'email' => $params['email'],
            'token' => $token
        ]);

        $invite = $organization->invites()->where('invitable_type', 'organization')
            ->where('email', $params['email'])->first();

        Mail::to($params['email'])->send(new OrganizationInvite($invite, $organization->name, $sender->name));
        return __('messages.mail.send.success');
    }
}
