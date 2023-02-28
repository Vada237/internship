<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateProject()
    {
        $this->seed();

        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $user->organizations()->attach($organization->id,['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

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
}
