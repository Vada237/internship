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
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $user->organizations()
            ->attach($organization->id, ['role_id' => Role::byName(Role::ORGANIZATION_SUPERVISOR)->first()->id]);

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
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

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
        $user = User::factory()->create();
        $organizations = Organization::factory()->count(2)->create();

        $user->organizations()->attach($organizations[0]->id,
            ['role_id' => Role::byName(Role::ORGANIZATION_SUPERVISOR)->first()->id]);

        $user->organizations()->attach($organizations[1]->id,
            ['role_id' => Role::byName(Role::USER)->first()->id]);

        $responce = $this->actingAs($user)->patchJson("api/organizations/{$organizations[1]->id}", [
            'name' => 'updated organization'
        ]);

        $responce->assertForbidden();
    }
}
