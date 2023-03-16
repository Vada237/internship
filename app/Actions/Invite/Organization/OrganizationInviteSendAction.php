<?php

namespace App\Actions\Invite\Organization;

use App\Mail\OrganizationInvite;
use App\Models\Invite;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrganizationInviteSendAction
{
    public function handle($params, User $sender)
    {
        $token = Str::random(60);

        $organization = Organization::find($params['organization_id']);

        $organization->invites()->create([
            'user_id' => $params['user_id'],
            'token' => $token
        ]);

        $invite = $organization->invites()->where('invitable_type', Invite::ORGANIZATION)
            ->where('user_id', $params['user_id'])->first();

        Mail::to(User::find($params['user_id'])->email)->send(new OrganizationInvite($invite, $organization->name, $sender->name));
        return __('messages.mail.send.success');
    }
}
