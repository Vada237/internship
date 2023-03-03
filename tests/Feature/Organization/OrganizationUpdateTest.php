<?php

namespace Tests\Feature\Organization;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganizationUpdateTest extends TestCase
{
    public function testUpdateYourselfOrganizationWithValidation()
    {
        $this->seed();
        $user = User::first();
        $organization = $user->organizations()->first();

        $response = $this->actingAs($user)->patch("api/organizations/$organization->id", [
            'name' => 'test'
        ]);

        $response->assertExactJson([
            'data' => [
                'id' => $organization->id,
                'name' => 'test'
            ]
        ]);
    }

    public function testUpdateYourselfOrganizationWithoutValidation()
    {
        $this->seed();
        $user = User::first();
        $organization = $user->organizations()->first();

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id
        ]);

        $response = $this->actingAs($user)->patchJson("api/organizations/$organization->id", [
            'name' => 'n'
        ]);

        $response->assertUnprocessable();
    }

    public function testUpdateAnotherOrganizationWithoutPermissionForbidden()
    {
        $this->seed();
        $user = User::offset(1)->first();
        $organizations = Organization::factory()->count(2)->create();

        $user->organizations()->attach($organizations[0]->id,
            ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $user->organizations()->attach($organizations[1]->id,
            ['role_id' => Role::byName(Role::list['USER'])->first()->id]);

        $responce = $this->actingAs($user)->patchJson("api/organizations/{$organizations[1]->id}", [
            'name' => 'updated organization'
        ]);

        $responce->assertForbidden();
    }
}
