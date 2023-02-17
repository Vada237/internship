<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    public function testCreateOrganizationWithoutValidation()
    {
        $user = User::factory()->create();

        $responce = $this->actingAs($user)->post('api/organizations', [
            'name' => 'n'
        ]);

        $responce->assertStatus(422);
    }

//    public function testCreateOrganizationWithValidation()
//    {
//
//        $user = User::factory()->create();
//
//        $responce = $this->actingAs($user)->post('api/organizations', [
//            'name' => 'Nokia'
//        ]);
//
//        $this->assertDatabaseHas('organizations', [
//            'id' => '1',
//            'name' => 'Nokia'
//        ]);
//        $responce->assertStatus(201);
//    }
//
//    public function testGetOrganization()
//    {
//        $user = User::factory()->create();
//        $organizations = Organization::factory()
//            ->has(User::factory()->count(2))
//            ->count(5)->create();
//
//
//        $response = $this->actingAs($user)->get('api/organizations/4/1');
//        $response->assertStatus(200);
//        $response->assertJsonStructure([
//            'data' => [
//                '*' => [
//                    'id',
//                    'name'
//                ]
//            ]
//        ]);
//    }

    public function testGetOrganizationById()
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

//    public function testUpdateOrganizationWithValidation() {
//         $user = User::factory()->hasAttached(
//             Organization::factory()
//         )->create();
//
//         $organization = $user->organizations()->first();
//
//         $response = $this->assertDatabaseHas('organizations',[
//             'id' => $organization->id
//         ]);
//
//
//
//         $response = $this->actingAs($user)->patch("api/organizations/$organization->id",[
//             'name' => 'test'
//         ]);
//
//        $response->assertStatus(200);
//        $response->assertJsonStructure([
//            'data' => [
//                'id',
//                'name'
//            ]
//        ]);
//    }
//
//    public function testUpdateOrganizationWithoutValidation() {
//        $user = User::factory()->hasAttached(
//            Organization::factory()
//        )->create();
//
//        $organization = $user->organizations()->first();
//
//        $response = $this->assertDatabaseHas('organizations',[
//            'id' => $organization->id
//        ]);
//
//        $response = $this->actingAs($user)->patch("api/organizations/$organization->id",[
//            'name' => 'n'
//        ]);
//
//        $response->assertStatus(422);
//    }
//
//    public function testDeleteOrganization() {
//        $user = User::factory()->hasAttached(
//            Organization::factory()
//        )->create();
//
//        $organization = $user->organizations()->first();
//
//        $response = $this->actingAs($user)->delete("api/organizations/$organization->id",[
//            'name' => 'test'
//        ]);
//
//        $response->assertStatus(200);
//    }
}
