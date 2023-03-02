<?php

namespace Tests\Feature\Project;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectUpdateTest extends TestCase
{
    public function testProjectUpdateSuccess()
    {
        $this->seed();

        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        $response = $this->actingAs($user)->patchJson("api/projects/$project->id", [
            'name' => 'edited project'
        ]);

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                'id' => Project::where('name', 'edited project')->first()->id,
                'name' => 'edited project',
                'organization_id' => Project::where('name', 'edited project')->first()->organization_id
            ]
        ]);
    }

    public function testNotEqualProjectUpdateNotFound()
    {
        $this->seed();
        $user = User::first();
        $notExistProjectId = Project::orderBy('id', 'DESC')->first()->id + 1;

        $response = $this->actingAs($user)->patchJson("api/projects/$notExistProjectId", [
            'name' => 'edited not equal project'
        ]);

        $response->assertNotFound();
    }

    public function testUpdateProjectWithoutPermissionForbidden()
    {
        $this->seed();

        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);

        $response = $this->actingAs($user)->patchJson("api/projects/$project->id", [
            'name' => 'edited project'
        ]);

        $response->assertForbidden();
    }

    public function testUpdateAnotherProjectWithAdminRoleSuccess()
    {
        $this->seed();
        $user = User::first();

        $project = Project::factory()->create();

        $response = $this->actingAs($user)->patchJson("api/projects/$project->id", [
            'name' => 'success edited project'
        ]);

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                'id' => Project::where('name', 'success edited project')->first()->id,
                'name' => 'success edited project',
                'organization_id' => Project::where('name', 'success edited project')->first()->organization_id
            ]
        ]);
    }

    public function testUpdateAnotherProjectWithoutPermissionForbidden()
    {
        $this->seed();

        $user = User::factory()->create();
        $projects = Project::factory()->count(2)->create();

        $user->projects()->attach($projects[0]->id,
            ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        $user->projects()->attach($projects[1]->id,
            ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);

        $response = $this->actingAs($user)->patchJson("api/projects/{$projects[1]->id}", [
            'name' => 'miraculously edited project'
        ]);

        $response->assertForbidden();
    }

    public function testUpdateProjectWithoutValidationUnprocessable()
    {
        $this->seed();
        $user = User::first();

        $project = Project::factory()->create();

        $response = $this->actingAs($user)->patchJson("api/projects/$project->id", [
            'name' => '1'
        ]);

        $response->assertUnprocessable();
    }

    public function testUpdateProjectUnauthorized()
    {
        $this->seed();
        $project = Project::factory()->create();

        $response = $this->patchJson("api/projects/$project->id", [
            'name' => 'edited project'
        ]);

        $response->assertUnauthorized();
    }
}
