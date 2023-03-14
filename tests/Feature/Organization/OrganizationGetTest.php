<?php

namespace Tests\Feature\Organization;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganizationGetTest extends TestCase
{
    public function testGetOrganizationSuccess()
    {
        Organization::factory()->count(5)->create();

        $user = User::factory()->create();
        $organization = Organization::first();

        $user->organizations()->attach($organization->id, ['role_id' => Role::byName(Role::list['ADMIN'])->first()->id]);

        $response = $this->actingAs($user)->getJson('api/organizations?limit=2&offset=0');

        $response->assertOk();
        $response->assertExactJson([
            "data" => [
                [
                    "id" => Organization::limit(1)->offset(0)->first()->id,
                    "name" => Organization::limit(1)->offset(0)->first()->name
                ],
                [
                    "id" => Organization::limit(1)->offset(1)->first()->id,
                    "name" => Organization::limit(1)->offset(1)->first()->name
                ]
            ]
        ]);
    }

    public function testGetAnotherOrganizationByIdForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $response = $this->actingAs($user)->getJson("/api/organizations/{$organization->id}");

        $response->assertForbidden();
    }

    public function testGetOrganizationByIdSuccess()
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create();

        $user->organizations()->attach($organization->id, ['role_id' => Role::byName(Role::list['ADMIN'])->first()->id]);

        $response = $this->actingAs($user)->getJson("/api/organizations/{$organization->id}");

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                'id' => $organization->id,
                'name' => $organization->name
            ]
        ]);
    }
}
