<?php

namespace Tests\Feature\Invite\Organization;

use App\Models\Invite;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrganizationInviteAcceptTest extends TestCase
{
    public function testAcceptInvite()
    {
        $user = User::factory()->create();
        $invitedUser = User::factory()->create();

        $organization = Organization::factory()->create();

        $user->organizations()
            ->attach($organization->id, ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $this->actingAs($user)->post('api/invites/send/organization', [
            'user_id' => $invitedUser->id,
            'organization_id' => $user->organizations()->first()->id
        ]);

        $invite = Invite::where('user_id', $invitedUser->id)
            ->where('invitable_id', $user->organizations()->first()->id)
            ->where('invitable_type', Invite::types['ORGANIZATION'])->first();

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
        $users = User::factory()->count(3)->create();

        $organization = Organization::factory()->create();

        $users[0]->organizations()
            ->attach($organization->id, ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        Invite::create([
            'user_id' => $users[1]->id,
            'invitable_id' => $organization->id,
            'invitable_type' => Invite::types['ORGANIZATION'],
            'token' => Str::random(60)
        ]);

        $invite = Invite::where('user_id', $users[1]->id)
            ->where('invitable_id', $organization->id)
            ->where('invitable_type', Invite::types['ORGANIZATION'])->first();

        $response = $this->actingAs($users[2])->get("api/invites/accept/$invite->token");
        $response->assertForbidden();
    }
}
