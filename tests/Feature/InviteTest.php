<?php

namespace Tests\Feature;

use App\Mail\OrganizationInvite;
use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InviteTest extends TestCase
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

    public function testAcceptInvite()
    {
        $this->seed();
        $user = User::first();
        $invitedUser = User::offset(1)->first();

        $this->actingAs($user)->post('api/invites/send', [
            'email' => $invitedUser->email,
            'organizationId' => $user->organizations()->first()->id
        ]);
        $invite = Invite::where('email', $invitedUser->email)->where('organization_id', $user->organizations()->first()->id)->first();

        $response = $this->actingAs($invitedUser)->get("api/invites/accept/$invite->token");

        $this->assertDatabaseHas('user_organization_roles', [
            'user_id' => $invitedUser->id,
            'organization_id' => $invite->organization_id,
            'role_id' => Role::where('slug', 'employee')->first()->id
        ]);
        $response->assertOk();
    }

    public function testAcceptInviteWithoutPermission()
    {
        $this->seed();

        $user = User::first();
        $invitedUser = User::offset(1)->first();
        $anotherUser = User::offset(2)->first();

        $this->actingAs($user)->post('api/invites/send', [
            'email' => $invitedUser->email,
            'organizationId' => $user->organizations()->first()->id
        ]);

        $invite = Invite::where('email', $invitedUser->email)
            ->where('organization_id', $user->organizations()->first()->id)->first();

        $response = $this->actingAs($anotherUser)->get("api/invites/accept/$invite->token");

        $response->assertForbidden();
    }
}
