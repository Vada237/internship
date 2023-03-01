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
        $this->seed();

        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $user->organizations()->attach($organization->id, ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $response = $this->actingAs($user)->postJson('api/projects', [
            'name' => 'Manhattan',
            'organizationId' => $organization->id
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

    public function testCreateProjectByUserWhoIsNotInThisOrganization()
    {
        $this->seed();

        $supervisor = User::factory()->create();
        $anotherUser = User::factory()->create();
        $organization = Organization::factory()->create();

        $supervisor->organizations()->attach($organization->id, ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $response = $this->actingAs($anotherUser)->postJson('api/projects', [
            'name' => 'Autochess',
            'organizationId' => $organization->id
        ]);

        $response->assertForbidden();
    }

    public function testCreateProjectByUserWithoutPermission()
    {
        $this->seed();
        $notEmployee = User::factory()->create();
        $organization = Organization::factory()->create();
        $notEmployee->organizations()->attach($organization->id, ['role_id' => Role::byName(Role::list['USER'])->first()->id]);

        $response = $this->actingAs($notEmployee)->postJson('api/projects', [
            'name' => 'Autochess',
            'organizationId' => $organization->id
        ]);

        $response->assertForbidden();
    }

    public function testCreateProjectWithoutAuth()
    {
        $this->seed();

        $response = $this->postJson('api/projects', [
            'name' => 'fake project',
            'organization_id' => Organization::first()->id
        ]);

        $response->assertUnauthorized();
    }
}
