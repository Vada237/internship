<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    public function test_create_organization_without_validation()
    {
        $user = User::factory()->create();

        $responce = $this->actingAs($user)->post('api/organizations', [
            'name' => 'n'
        ]);

        $responce->assertStatus(422);
    }

    public function test_create_organization_with_validation()
    {

        $user = User::factory()->create();

        $responce = $this->actingAs($user)->post('api/organizations', [
            'name' => 'Nokia'
        ]);

        $this->assertDatabaseHas('organizations', [
            'id' => '1',
            'name' => 'Nokia'
        ]);
        $responce->assertStatus(201);
    }

    public function test_get_organization()
    {
        $user = User::factory()->create();
        $organizations = Organization::factory()
            ->has(User::factory()->count(2))
            ->count(5)->create();


        $response = $this->actingAs($user)->get('api/organizations/4/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name'
                ]
            ]
        ]);
    }

    public function test_get_organization_by_id()
    {

        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $response = $this->actingAs($user)->get("/api/organizations/{$organization->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name'
            ]
        ]);
    }
}
