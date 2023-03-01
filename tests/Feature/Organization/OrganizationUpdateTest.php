<?php

namespace Tests\Feature\Organization;

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
}
