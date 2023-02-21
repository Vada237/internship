<?php

namespace Tests\Feature;

use App\Http\Resources\OrganizationResource;
use App\Http\Resources\UserResource;
use App\Models\Organization;
use App\Models\User;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    public function testCreateOrganizationWithoutValidation()
    {
        $this->seed();

        $responce = $this->actingAs(User::first())->post('api/organizations', [
            'name' => 'n'
        ]);

        $responce->assertUnprocessable();
    }

    public function testCreateOrganizationWithValidation()
    {
        $this->seed();
        $user = User::first();
        $response = $this->actingAs($user)->post('api/organizations', [
            'name' => 'Nokia'
        ]);

        $organization = $user->organizations()->where('name', 'Nokia')->first();

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'name' => $organization->name
        ]);

        $response->assertCreated();
    }

    public function testGetOrganization()
    {
        $this->seed();

        $response = $this->actingAs(User::first())->get('api/organizations?limit=2&offset=0');
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

    public function testGetOrganizationById()
    {
        $this->seed();

        $organization = Organization::first();

        $response = $this->actingAs(User::first())->get("/api/organizations/{$organization->id}");

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name'
            ]
        ]);
    }

    public function testUpdateYourselfOrganizationWithValidation() {

         $this->seed();
         $user = User::first();

         $organization = $user->organizations()->first();

         $response = $this->actingAs($user)->patch("api/organizations/$organization->id",[
             'name' => 'test'
         ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name'
            ]
        ]);
    }

    public function testUpdateYourselfOrganizationWithoutValidation() {

        $this->seed();
        $user = User::first();
        $organization = $user->organizations()->first();

        $response = $this->assertDatabaseHas('organizations',[
            'id' => $organization->id
        ]);

        $response = $this->actingAs($user)->patch("api/organizations/$organization->id",[
            'name' => 'n'
        ]);

        $response->assertUnprocessable();
    }

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

    public function testDeleteWithoutPermission()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete("api/organizations/1000000", [
            'name' => 'test'
        ]);

        $response->assertForbidden();
    }
}
