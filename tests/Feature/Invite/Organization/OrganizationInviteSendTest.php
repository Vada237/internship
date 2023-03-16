<?php

namespace Tests\Feature\Invite\Organization;

use App\Mail\OrganizationInvite;
use App\Models\Invite;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrganizationInviteSendTest extends TestCase
{
    public function testSendInviteSuccess()
    {
        Mail::fake();

        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $user->organizations()
            ->attach($organization->id, ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $invitedUser = User::factory()->create();

        $this->actingAs($user)->postJson('api/invites/send/organization', [
            'user_id' => $invitedUser->id,
            'organization_id' => $organization->id
        ]);

        $this->assertDatabaseHas('invites', [
            'user_id' => $invitedUser->id,
            'invitable_type' => Invite::types['ORGANIZATION'],
            'invitable_id' => $organization->id
        ]);

        Mail::assertSent(OrganizationInvite::class);
    }

    public function testSendInviteWithoutValidation()
    {
        Mail::fake();

        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $user->organizations()
            ->attach($organization->id, ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $invitedUser = User::factory()->create();

        $response = $this->actingAs($user)->postJson('api/invites/send/organization', [
            'user_id' => $invitedUser->id
        ]);

        Mail::assertNotSent(OrganizationInvite::class);
        $response->assertUnprocessable();
    }

    public function testSendInviteWithoutPermission()
    {
        Mail::fake();

        $organization = Organization::factory()->create();
        $fakeSupervisor = User::factory()->create();
        $invitedUser = User::factory()->create();

        $fakeSupervisor->organizations()
            ->attach($organization->id, ['role_id' => Role::byName(Role::list['USER'])->first()->id]);

        $response = $this->actingAs($fakeSupervisor)->postJson('api/invites/send/organization', [
            'user_id' => $invitedUser->id,
            'organization_id' => $organization->id
        ]);

        Mail::assertNotSent(OrganizationInvite::class);
        $response->assertForbidden();
    }
}
