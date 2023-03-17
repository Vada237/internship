<?php

namespace Tests\Feature\Project;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class ProjectCreateTest extends TestCase
{
    public function testCreateProjectSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::ORGANIZATION_SUPERVISOR)->first()->id]);

        $response = $this->actingAs($user)->postJson('api/projects', [
            'name' => 'Manhattan',
            'organization_id' => $organization->id
        ]);

        $this->assertDatabaseHas('projects', [
            'id' => $user->projects()->where('name', 'Manhattan')->first()->id,
            'name' => $user->projects()->where('name', 'Manhattan')->first()->name,
            'organization_id' => $user->projects()->where('name', 'Manhattan')->first()->organization_id
        ]);
        $response->assertCreated();
    }

    public function testCreateProjectWithoutValidation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('api/projects', [
            'name' => 'Manhattan'
        ]);

        $response->assertUnprocessable();
    }

    public function testCreateProjectByUserWithoutPermission()
    {
        $notEmployee = User::factory()->create();
        $organization = Organization::factory()->create();

        $notEmployee->organizations()->attach($organization->id, ['role_id' => Role::byName(Role::USER)->first()->id]);

        $response = $this->actingAs($notEmployee)->postJson('api/projects', [
            'name' => 'Autochess',
            'organization_id' => $organization->id
        ]);

        $response->assertForbidden();
    }

    public function testCreateProjectUnauthorized()
    {
        Organization::factory()->create();

        $response = $this->postJson('api/projects', [
            'name' => 'fake project',
            'organization_id' => Organization::first()->id
        ]);

        $response->assertUnauthorized();
    }
}
