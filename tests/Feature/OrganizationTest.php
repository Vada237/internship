<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    public function test_create_organization_without_validation()
    {
        $user = User::factory()->create();

        $responce = $this->actingAs($user)->post('api/organization', [
            'name' => 'n'
        ]);

        $responce->assertStatus(422);
    }

    public function test_create_organization_with_validation()
    {

        $user = User::factory()->create();

        $responce = $this->actingAs($user)->post('api/organization', [
            'name' => 'Nokia'
        ]);

        $responce->assertStatus(201);
    }

    public function test_get_organization()
    {
        $user = User::factory()->create();
        $organizations = [
            [
                'id' => 1,
                'name' => 'Nokia'
            ],
            [
                'id' => 2,
                'name' => 'Microsoft'
            ]
        ];

        $responce = $this->actingAs($user)->get('api/organization');

        $responce->assertStatus(200);
        $responce->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name'
                ]
            ]
        ]);
    }

    /*public function test_get_organization_by_id () {
        $user = User::factory()->create();
        $organization = new Organization([
            'id' => 1,
            'name' => 'Nokia'
        ]);

        $response = $this->actingAs($user)->get('api/organization/id=', ['id' => 1]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                    'id',
                    'name'
                ]
            ]);
    }*/
}
