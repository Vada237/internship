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
        $this->seed();
        $user = User::offset(1)->first();
        $project = Project::create([
            'name' => 'project',
            'organization_id' => $user->organizations()->first()->id
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
        $this->seed();
        $user = User::offset(1)->first();
        $notExistProjectId = Project::OrderBy('id', 'DESC')->first()->id + 1;

        $response = $this->actingAs($user)->getJson("api/projects/$notExistProjectId");

        $response->assertNotFound();
    }

    public function testGetProjectByIdWithAdminRoleSuccess()
    {
        $this->seed();

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
        $this->seed();

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
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $response = $this->getJson("api/projects/$project->id");

        $response->assertUnauthorized();
    }
}
