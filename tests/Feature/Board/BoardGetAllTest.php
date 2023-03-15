<?php

namespace Tests\Feature\Board;

use App\Models\Board;
use App\Models\BoardTemplate;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardGetAllTest extends TestCase
{
    public function testBoardGetAllSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['ADMIN'])->first()->id]);

        BoardTemplate::factory()->create();
        Board::factory()->count(2)->create();

        $limit = 2;
        $offset = 0;

        $response = $this->actingAs($user)->getJson("api/boards?limit=$limit&offset=$offset");

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                [
                    'id' => Board::first()->id,
                    'name' => Board::first()->name,
                    'project_id' => Board::first()->project_id,
                    'status' => Board::first()->status
                ],
                [
                    'id' => Board::offset(1)->first()->id,
                    'name' => Board::offset(1)->first()->name,
                    'project_id' => Board::offset(1)->first()->project_id,
                    'status' => Board::offset(1)->first()->status
                ]
            ]
        ]);
    }

    public function testBoardGetAllForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['USER'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);

        BoardTemplate::factory()->create();
        Board::factory()->count(5)->create();

        $limit = 2;
        $offset = 0;

        $response = $this->actingAs($user)->getJson("api/boards?limit=$limit&offset=$offset");

        $response->assertForbidden();
    }

    public function testBoardGetAllUnauthorized()
    {
        User::factory()->create();
        Organization::factory()->create();
        Project::factory()->create();

        BoardTemplate::factory()->create();
        Board::factory()->count(5)->create();

        $limit = 2;
        $offset = 1;

        $response = $this->getJson("api/boards?limit=$limit&offset=$offset");

        $response->assertUnauthorized();
    }
}
