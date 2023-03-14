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
    public function testSendInvite()
    {
        $this->seed();
        Mail::fake();

        $user = User::first();
        $invitedUser = User::offset(1)->first();

        $response = $this->actingAs($user)->post('api/invites/send/organization', [
            'email' => $invitedUser->email,
            'organizationId' => $user->organizations()->first()->id
        ]);

        $this->assertDatabaseHas('invites', [
            'email' => $invitedUser->email,
            'invitable_type' => 'organization',
            'invitable_id' => $user->organizations()->first()->id
        ]);

        Mail::assertSent(OrganizationInvite::class);
    }

    public function testSendInviteWithoutValidation()
    {
        $this->seed();
        Mail::fake();

        $user = User::first();
        $invitedUser = User::offset(1)->first();

        $response = $this->actingAs($user)->postJson('api/invites/send/organization', [
            'email' => $invitedUser->email
        ]);

        Mail::assertNotSent(OrganizationInvite::class);
        $response->assertUnprocessable();
    }

    public function testSendInviteWithoutPermission()
    {
        $this->seed();

        Mail::fake();

        $organization = Organization::factory()->create();
        $fakeSupervisor = User::factory()->create();
        $invitedUser = User::offset(1)->first();

        $fakeSupervisor->organizations()->attach($organization->id, ['role_id' => Role::byName(Role::list['USER'])->first()->id]);

        $response = $this->actingAs($fakeSupervisor)->postJson('api/invites/send/organization', [
            'email' => $invitedUser->email,
            'organizationId' => $fakeSupervisor->organizations()->first()->id
        ]);

        Mail::assertNotSent(OrganizationInvite::class);
        $response->assertForbidden();
    }
}
