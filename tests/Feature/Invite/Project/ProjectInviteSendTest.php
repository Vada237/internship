<?php

namespace Tests\Feature\Invite\Project;

use App\Mail\ProjectInvite;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProjectInviteSendTest extends TestCase
{
    public function testProjectSendInviteSuccess()
    {
        $this->seed();

        Mail::fake();

        $user = User::factory()->create();
        $invitedUser = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::create([
            'name' => 'project for invite',
            'organization_id' => $organization->id
        ]);

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        $response = $this->actingAs($user)->postJson('api/invites/send/project', [
            'email' => $invitedUser->email,
            'projectId' => $project->id
        ]);

        Mail::assertSent(ProjectInvite::class);

        $this->assertDatabaseHas('invites', [
            'email' => $invitedUser->email,
            'invitable_type' => 'project',
            'invitable_id' => $project->id
        ]);

    }

    public function testProjectSendInviteUnprocessableEntity()
    {
        $this->seed();

        Mail::fake();

        $user = User::first();
        $invitedUser = User::factory()->create();
        $organization = Organization::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $response = $this->actingAs($user)->postJson('api/invites/send/project', [
            'email' => $invitedUser->email
        ]);

        $response->assertUnprocessable();
    }

    public function testProjectSendInviteForbidden()
    {
        $this->seed();

        Mail::fake();

        $user = User::factory()->create();
        $invitedUser = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::create([
            'name' => 'project for invite',
            'organization_id' => $organization->id
        ]);

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['USER'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);

        $response = $this->actingAs($user)->postJson('api/invites/send/project', [
            'email' => $invitedUser->email,
            'projectId' => $project->id
        ]);

        $response->assertForbidden();
    }

    public function testProjectSendInviteUnauthorized()
    {
        $this->seed();

        $invitedUser = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::create([
            'name' => 'project for invite',
            'organization_id' => $organization->id
        ]);

        $response = $this->postJson('api/invites/send/project', [
            'email' => $invitedUser->email,
            'projectId' => $project->id
        ]);

        $response->assertUnauthorized();
    }
}
