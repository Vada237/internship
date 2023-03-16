<?php

namespace Tests\Feature\Project;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectDeleteTest extends TestCase
{
    public function testDeleteProjectSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $project = Project::create([
            'name' => 'project',
            'organization_id' => $organization->id
        ]);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => $project->name,
            'organization_id' => $organization->id
        ]);

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        $response = $this->actingAs($user)->deleteJson("api/projects/$project->id");

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
            'name' => $project->name,
            'organization_id' => $project->organization_id
        ]);

        $response->assertOk();
    }

    public function testDeleteProjectFromOrganizationSupervisorWithoutProjectPermissionSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::create([
            'name' => 'proj',
            'organization_id' => $organization->id
        ]);

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);

        $response = $this->actingAs($user)->deleteJson("api/projects/$project->id");

        $this->actingAs($user)->assertDatabaseMissing('projects', [
            'id' => $project->id,
            'name' => $project->name,
            'organization_id' => $project->organizaiton_id
        ]);

        $response->assertOk();
    }

    public function testDeleteProjectWithoutPermissionForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['USER'])->first()->id]);


        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);

        $response = $this->actingAs($user)->deleteJson("api/projects/$project->id");

        $response->assertForbidden();
    }

    public function testDeleteAnotherProjectWithoutPermissionForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();
        $anotherProject = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['USER'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        $user->projects()->attach($anotherProject->id,
            ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);

        $response = $this->actingAs($user)->deleteJson("api/projects/$anotherProject->id");

        $response->assertForbidden();
    }

    public function testDeleteAnotherProjectUnauthorized()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        $response = $this->deleteJson("api/projects/$project->id");

        $response->assertUnauthorized();
    }
}
