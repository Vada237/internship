<?php

namespace Tests\Feature\Invite\Project;

use App\Mail\ProjectInvite;
use App\Models\Invite;
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
        Mail::fake();

        $user = User::factory()->create();
        $invitedUser = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::create([
            'name' => 'project for invite',
            'organization_id' => $organization->id
        ]);

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::ORGANIZATION_SUPERVISOR)->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_SUPERVISOR)->first()->id]);

        $this->actingAs($user)->postJson('api/invites/send/project', [
            'user_id' => $invitedUser->id,
            'project_id' => $project->id
        ]);

        Mail::assertSent(ProjectInvite::class);

        $this->assertDatabaseHas('invites', [
            'user_id' => $invitedUser->id,
            'invitable_type' => Invite::PROJECT,
            'invitable_id' => $project->id
        ]);

    }

    public function testProjectSendInviteUnprocessableEntity()
    {
        Mail::fake();

        $user = User::factory()->create();
        $invitedUser = User::factory()->create();
        $organization = Organization::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::ORGANIZATION_SUPERVISOR)->first()->id]);

        $response = $this->actingAs($user)->postJson('api/invites/send/project', [
            'user_id' => $invitedUser->id
        ]);

        $response->assertUnprocessable();
    }

    public function testProjectSendInviteForbidden()
    {
        Mail::fake();

        $user = User::factory()->create();
        $invitedUser = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::create([
            'name' => 'project for invite',
            'organization_id' => $organization->id
        ]);

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::USER)->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_PARTICIPANT)->first()->id]);

        $response = $this->actingAs($user)->postJson('api/invites/send/project', [
            'user_id' => $invitedUser->id,
            'project_id' => $project->id
        ]);

        $response->assertForbidden();
    }

    public function testProjectSendInviteUnauthorized()
    {
        $invitedUser = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::create([
            'name' => 'project for invite',
            'organization_id' => $organization->id
        ]);
        $response = $this->postJson('api/invites/send/project', [
            'user_id' => $invitedUser->id,
            'projectId' => $project->id
        ]);

        $response->assertUnauthorized();
    }
}
