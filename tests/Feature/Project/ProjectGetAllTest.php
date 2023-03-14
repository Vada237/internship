<?php

namespace Tests\Feature\Project;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectGetAllTest extends TestCase
{
    public function testGetAllProjectsSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        Project::factory()->count(5)->create();

        $user->organizations()
            ->attach($organization->id, ['role_id' => Role::byName(Role::list['ADMIN'])->first()->id]);

        $response = $this->actingAs($user)->getJson('api/projects?limit=2&offset=0');

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                [
                    'id' => Project::first()->id,
                    'name' => Project::first()->name,
                    'organization_id' => Project::first()->organization_id
                ],
                [
                    'id' => Project::offset(1)->first()->id,
                    'name' => Project::offset(1)->first()->name,
                    'organization_id' => Project::offset(1)->first()->organization_id
                ]
            ]
        ]);
    }

    public function testGetAllProjectsWithoutAdminRoleForbidden()
    {
        $user = User::factory()->create();

        Organization::factory()->create();
        Project::factory()->count(2)->create();

        $response = $this->actingAs($user)->getJson('api/projects?limit=2&offset=0');
        $response->assertForbidden();
    }

    public function testGetAllProjectsUnauthorized()
    {
        Organization::factory()->create();
        Project::factory()->count(2)->create();

        $response = $this->getJson('api/projects?limit=2&offset=0');
        $response->assertUnauthorized();
    }

    public function testGetAllProjectsWithoutValidation()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        Project::factory()->count(2)->create();

        $user->organizations()
            ->attach($organization->id, ['role_id' => Role::byName(Role::list['ADMIN'])->first()->id]);

        $response = $this->actingAs($user)->getJson('api/projects?limit=infinity&offset=zero');
        $response->assertUnprocessable();
    }
}
