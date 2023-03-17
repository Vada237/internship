<?php

namespace Tests\Feature\Organization;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganizationCreateTest extends TestCase
{
    public function testCreateOrganizationWithoutValidation()
    {
        $responce = $this->actingAs(User::factory()->create())->postJson('api/organizations', [
            'name' => 'n'
        ]);

        $responce->assertUnprocessable();
    }

    public function testCreateOrganizationWithValidation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('api/organizations', [
            'name' => 'Nokia'
        ]);

        $this->assertDatabaseHas('organizations', [
            'id' => $user->organizations()->where('name', 'Nokia')->first()->id,
            'name' => $user->organizations()->where('name', 'Nokia')->first()->name
        ]);

        $response->assertCreated();
    }
}
