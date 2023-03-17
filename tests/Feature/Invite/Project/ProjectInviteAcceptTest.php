<?php

namespace Tests\Feature\Invite\Project;

use App\Models\Invite;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProjectInviteAcceptTest extends TestCase
{
    public function testProjectInviteAcceptSuccess()
    {
        $user = User::factory()->create();

        $organization = Organization::factory()->create();

        $project = Project::create([
            'name' => 'invite project',
            'organization_id' => $organization->id
        ]);

        $invite = Invite::create([
            'user_id' => $user->id,
            'token' => Str::random(60),
            'invitable_id' => $project->id,
            'invitable_type' => Invite::PROJECT
        ]);

        $response = $this->actingAs($user)->getJson("api/invites/accept/project/$invite->token");

        $this->assertDatabaseHas('user_project_roles', [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'role_id' => Role::byName(Role::PROJECT_PARTICIPANT)->first()->id
        ]);

        $response->assertOk();
    }

    public function testProjectInviteAcceptFromAnotherUserForbidden()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $organization = Organization::factory()->create();
        $project = Project::create([
            'name' => 'invite project',
            'organization_id' => $organization->id
        ]);

        $invite = Invite::create([
            'user_id' => $user->id,
            'token' => Str::random(60),
            'invitable_id' => $project->id,
            'invitable_type' => Invite::PROJECT
        ]);

        $response = $this->actingAs($anotherUser)->getJson("api/invites/accept/project/$invite->token");

        $response->assertForbidden();
    }
}
