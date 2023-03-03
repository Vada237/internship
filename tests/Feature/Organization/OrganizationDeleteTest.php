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
    public function testDeleteYourselfOrganization()
    {
        $this->seed();
        $user = User::first();
        $organization = $user->organizations()->first();

        $response = $this->actingAs($user)->delete("api/organizations/$organization->id", [
            'name' => 'test'
        ]);

        $response->assertOk();
    }

    public function testDeleteOrganizationWithoutPermission()
    {
        $this->seed();

        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $user->organizations()->attach($organization->id, ['role_id' => Role::byName(Role::list['USER'])->first()->id]);

        $response = $this->actingAs($user)->deleteJson("api/organizations/$organization->id");

        $response->assertForbidden();
    }
}
