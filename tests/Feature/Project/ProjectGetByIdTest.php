<?php

namespace Tests\Feature\Project;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectGetByIdTest extends TestCase
{
    public function testGetProjectByIdSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $project = Project::create([
            'name' => 'project',
            'organization_id' => $organization->id
        ]);

        $user->organizations()->attach($organization->id, [
            'role_id' => Role::byName(Role::list['USER'])->first()->id
        ]);

        $user->projects()->attach($project->id, [
            'role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id
        ]);

        $response = $this->actingAs($user)->getJson("api/projects/$project->id");

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                'id' => $project->id,
                'name' => $project->name,
                'organization_id' => $project->organization_id
            ]
        ]);
    }

    public function testGetProjectByIdNotFound()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        Project::factory()->count(5)->create();

        $user->organizations()->attach($organization->id, [
            'role_id' => Role::byName(Role::list['ADMIN'])->first()->id
        ]);

        $notExistProjectId = Project::OrderBy('id', 'DESC')->first()->id + 1;

        $response = $this->actingAs($user)->getJson("api/projects/$notExistProjectId");

        $response->assertNotFound();
    }

    public function testGetProjectByIdWithAdminRoleSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $anotherOrganization = Organization::factory()->create();
        $project = Project::create([
            'name' => 'project',
            'organization_id' => $anotherOrganization->id
        ]);

        $user->organizations()->attach($organization->id, ['role_id' => Role::byName(Role::list['ADMIN'])->first()->id]);

        $response = $this->actingAs($user)->getJson("api/projects/$project->id");

        $response->assertOk();

        $response->assertExactJson([
            'data' => [
                'id' => $project->id,
                'name' => $project->name,
                'organization_id' => $project->organization_id
            ]
        ]);
    }

    public function testGetProjectByIdPermission()
    {
        $user = User::factory()->create();
        $anotherOrganization = Organization::factory()->create();
        $project = Project::create([
            'name' => 'project',
            'organization_id' => $anotherOrganization->id
        ]);

        $response = $this->actingAs($user)->getJson("api/projects/$project->id");

        $response->assertForbidden();
    }

    public function testGetProjectByIdUnauthorized()
    {
        Organization::factory()->create();
        $project = Project::factory()->create();

        $response = $this->getJson("api/projects/$project->id");

        $response->assertUnauthorized();
    }
}
