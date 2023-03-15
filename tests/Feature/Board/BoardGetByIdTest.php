<?php

namespace Tests\Feature\Board;

use App\Models\Board;
use App\Models\BoardTemplate;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class BoardGetByIdTest extends TestCase
{
    public function testBoardGetByIdSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['USER'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);

        BoardTemplate::factory()->create();
        $board = Board::factory()->create();

        $response = $this->actingAs($user)->getJson("api/boards/$board->id");

        $response->assertOk();
        $response->assertExactJson([
            'data' =>
                [
                    'id' => Board::first()->id,
                    'name' => Board::first()->name,
                    'project_id' => Board::first()->project_id,
                    'status' => Board::first()->status
                ]
        ]);
    }

    public function testBoardGetByIdForbidden()
    {
        $user = User::factory()->create();

        $organization = Organization::factory()->create();

        $project = Project::factory()->create();
        $anotherProject = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['USER'])->first()->id]);

        $user->projects()->attach($anotherProject->id,
            ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        BoardTemplate::factory()->create();
        $board = Board::create([
            'name' => 'board',
            'project_id' => $project->id,
            'status' => 'edited'
        ]);

        $response = $this->actingAs($user)->getJson("api/boards/$board->id");

        $response->assertForbidden();
    }

    public function testBoardGetByIdUnauthorized()
    {
        User::factory()->create();
        Organization::factory()->create();
        Project::factory()->create();

        BoardTemplate::factory()->create();
        $board = Board::factory()->create();

        $response = $this->getJson('api/boards/' . $board->id);
        $response->assertUnauthorized();
    }
}
