<?php

namespace Tests\Feature\Project;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectGetByOrganizationTest extends TestCase
{
    public function testGetProjectsByOrganizationSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $user->organizations()->attach($organization->id, ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        Project::create([
            'name' => 'First project',
            'organization_id' => $organization->id
        ]);

        Project::create([
            'name' => 'Second project',
            'organization_id' => $organization->id
        ]);

        $response = $this->actingAs($user)->getJson("api/projects/find-by-organization/$organization->id");

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                [
                    'id' => Project::where('name', 'First project')->first()->id,
                    'name' => Project::where('name', 'First project')->first()->name,
                    'organization_id' => Project::where('name', 'First project')->first()->organization_id
                ],
                [
                    'id' => Project::where('name', 'Second project')->first()->id,
                    'name' => Project::where('name', 'Second project')->first()->name,
                    'organization_id' => Project::where('name', 'Second project')->first()->organization_id
                ]
            ]
        ]);
    }

    public function testGetProjectByOrganizationNotFound()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $user->organizations()->attach($organization->id, [
            'role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id
        ]);
        $notExistOrganizationId = Organization::OrderBy('id', 'DESC')->first()->id + 1;

        $response = $this->actingAs($user)->getJson("api/projects/find-by-organization/$notExistOrganizationId");

        $response->assertNotFound();
    }

    public function testGetProjectByOrganizationFromAnotherCompanySupervisorOrEmployeeForbidden()
    {
        $users = User::factory()->count(2)->create();
        $organizations = Organization::factory()->count(2)->create();

        $users[0]->organizations()->attach($organizations[0]->id, ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);
        $users[1]->organizations()->attach($organizations[1]->id, ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $response = $this->actingAs($users[0])->getJson("api/projects/find-by-organization/{$organizations[1]->id}");

        $response->assertForbidden();
    }

    public function testGetProjectByOrganizationUnauthorized()
    {
        $organization = Organization::factory()->create();
        Project::create([
            'name' => 'project',
            'organization_id' => $organization->id
        ]);

        $response = $this->getJson("api/projects/find-by-organization/$organization->id");
        
        $response->assertUnauthorized();
    }
}
