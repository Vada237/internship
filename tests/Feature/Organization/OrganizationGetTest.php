<?php

namespace Tests\Feature\Organization;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganizationGetTest extends TestCase
{
    public function testGetOrganization()
    {
        $this->seed();

        $response = $this->actingAs(User::first())->getJson('api/organizations?limit=2&offset=0');

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

    public function testGetAnotherOrganizationById()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $response = $this->actingAs($user)->getJson("/api/organizations/{$organization->id}");

        $response->assertForbidden();
    }

    public function testGetOrganizationById()
    {
        $this->seed();
        $organization = Organization::first();

        $response = $this->actingAs(User::first())->getJson("/api/organizations/{$organization->id}");

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                'id' => $organization->id,
                'name' => $organization->name
            ]
        ]);
    }
}
