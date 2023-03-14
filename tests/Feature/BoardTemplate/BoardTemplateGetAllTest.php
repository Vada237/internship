<?php

namespace Tests\Feature\BoardTemplate;

use App\Models\BoardTemplate;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardTemplateGetAllTest extends TestCase
{
    public function testBoardTemplateGetAllSuccess()
    {
        $user = User::factory()->create();
        BoardTemplate::factory()->count(5)->create();
        $organization = Organization::factory()->create();

        $user->organizations()->attach($organization->id, [
            'role_id' => Role::byName(Role::list['ADMIN'])->first()->id
        ]);

        $limit = 2;
        $offset = 1;

        $response = $this->actingAs($user)->getJson("api/board-templates?limit=$limit&offset=$offset");

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                [
                    'id' => BoardTemplate::offset(1)->first()->id,
                    'name' => BoardTemplate::offset(1)->first()->name,
                    'creater' => BoardTemplate::offset(1)->first()->user()->first()->name
                ],
                [
                    'id' => BoardTemplate::offset(2)->first()->id,
                    'name' => BoardTemplate::offset(2)->first()->name,
                    'creater' => BoardTemplate::offset(2)->first()->user()->first()->name
                ]
            ]
        ]);
    }

    public function testBoardTemplateGetAllUnauthorized()
    {
        User::factory()->create();
        BoardTemplate::factory()->count(5)->create();
        $limit = 2;
        $offset = 1;

        $response = $this->getJson("api/board-templates?limit=$limit&offset=$offset");

        $response->assertUnauthorized();
    }
}
