<?php

namespace Tests\Feature\Project;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectGetTest extends TestCase
{
    public function testGetProjectSuccess()
    {
        $this->seed();

        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $user->organizations()->attach($organization->id, ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $firstProject = Project::create([
            'name' => 'First project',
            'organization_id' => $organization->id
        ]);

        $secondProject = Project::create([
            'name' => 'Second project',
            'organization_id' => $organization->id
        ]);

        $response = $this->actingAs($user)->getJson("api/project?organizationId=$organization->id");

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                [
                    'id' => $firstProject->id,
                    'name' => $firstProject->name,
                    'organization_id' => $firstProject->organization_id
                ],
                [
                    'id' => $secondProject->id,
                    'name' => $secondProject->name,
                    'organization_id' => $secondProject->organization_id
                ]
            ]
        ]);
    }
}
