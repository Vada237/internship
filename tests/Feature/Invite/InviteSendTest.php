<?php

namespace Tests\Feature\Invite;

use App\Mail\OrganizationInvite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InviteSendTest extends TestCase
{
    public function testSendInvite()
    {
        $this->seed();
        Mail::fake();

        $user = User::first();
        $invitedUser = User::offset(1)->first();

        $response = $this->actingAs($user)->post('api/invites/send', [
            'email' => $invitedUser->email,
            'organizationId' => $user->organizations()->first()->id
        ]);

        $this->assertDatabaseHas('invites', [
            'email' => $invitedUser->email,
            'organization_id' => $user->organizations()->first()->id
        ]);

        Mail::assertSent(OrganizationInvite::class);
    }

    public function testSendInviteWithoutValidation()
    {
        $this->seed();
        Mail::fake();

        $user = User::first();
        $invitedUser = User::offset(1)->first();

        $response = $this->actingAs($user)->postJson('api/invites/send', [
            'email' => $invitedUser->email
        ]);

        Mail::assertNotSent(OrganizationInvite::class);
        $response->assertUnprocessable();
    }

    public function testSendInviteWithoutPermission()
    {
        $this->seed();
        Mail::fake();

        $supervisor = User::first();
        $fakeSupervisor = User::factory()->create();
        $invitedUser = User::offset(1)->first();

        $response = $this->actingAs($fakeSupervisor)->postJson('api/invites/send', [
            'email' => $invitedUser->email,
            'organizationId' => $supervisor->organizations()->first()->id
        ]);

        Mail::assertNotSent(OrganizationInvite::class);
        $response->assertForbidden();
    }
}
