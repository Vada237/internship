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
        $this->seed();

        $responce = $this->actingAs(User::first())->postJson('api/organizations', [
            'name' => 'n'
        ]);

        $responce->assertUnprocessable();
    }

    public function testCreateOrganizationWithValidation()
    {
        $this->seed();
        $user = User::first();

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