<?php

namespace Tests\Feature\Organization;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganizationDeleteTest extends TestCase
{
    public function testDeleteYourselfOrganizationSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $user->organizations()
            ->attach($organization->id, ['role_id' => Role::byName(Role::ORGANIZATION_SUPERVISOR)->first()->id]);

        $response = $this->actingAs($user)->delete("api/organizations/$organization->id");

        $response->assertOk();
    }

    public function testDeleteOrganizationWithoutPermission()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $user->organizations()->attach($organization->id, ['role_id' => Role::byName(Role::USER)->first()->id]);

        $response = $this->actingAs($user)->deleteJson("api/organizations/$organization->id");

        $response->assertForbidden();
    }
}
