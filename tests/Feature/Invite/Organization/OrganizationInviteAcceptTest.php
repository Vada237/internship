<?php

namespace Tests\Feature\Invite\Organization;

use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class OrganizationInviteAcceptTest extends TestCase
{
    public function testAcceptInvite()
    {
        $this->seed();
        $user = User::first();
        $invitedUser = User::offset(1)->first();

        $this->actingAs($user)->post('api/invites/send/organization', [
            'email' => $invitedUser->email,
            'organizationId' => $user->organizations()->first()->id
        ]);

        $invite = Invite::where('email', $invitedUser->email)
            ->where('invitable_id', $user->organizations()->first()->id)
            ->where('invitable_type', 'organization')->first();

        $response = $this->actingAs($invitedUser)->get("api/invites/accept/$invite->token");

        $this->assertDatabaseHas('user_organization_roles', [
            'user_id' => $invitedUser->id,
            'organization_id' => $invite->invitable_id,
            'role_id' => Role::byName(Role::list['EMPLOYEE'])->first()->id
        ]);
        $response->assertOk();
    }

    public function testAcceptInviteWithoutPermission()
    {
        $this->seed();

        $user = User::first();
        $invitedUser = User::offset(1)->first();
        $anotherUser = User::offset(2)->first();

        $this->actingAs($user)->post('api/invites/send/organization', [
            'email' => $invitedUser->email,
            'organizationId' => $user->organizations()->first()->id
        ]);

        $invite = Invite::where('email', $invitedUser->email)
            ->where('invitable_id', $user->organizations()->first()->id)
            ->where('invitable_type', 'organization')->first();

        $response = $this->actingAs($anotherUser)->get("api/invites/accept/$invite->token");

        $response->assertForbidden();
    }
}
