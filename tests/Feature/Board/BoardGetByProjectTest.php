<?php

namespace Tests\Feature\Board;

use App\Models\Board;
use App\Models\BoardTemplate;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class BoardGetByProjectTest extends TestCase
{
    public function testBoardGetByProjectIdSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id, [
            'role_id' => Role::byName(Role::list['USER'])->first()->id
        ]);

        $user->projects()->attach($project->id, [
            'role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id
        ]);

        BoardTemplate::factory()->create();
        Board::factory()->count(2)->create();

        $response = $this->actingAs($user)->getJson('api/boards/find-by-project/'.$project->id);

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

    public function testBoardGetByProjectIdForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $project = Project::factory()->create();
        $anotherProject = Project::factory()->create();

        $user->organizations()->attach($organization->id, [
            'role_id' => Role::byName(Role::list['USER'])->first()->id
        ]);

        $user->projects()->attach($anotherProject->id, [
            'role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id
        ]);

        BoardTemplate::factory()->create();
        Board::create([
            'name' => 'board',
            'project_id' => $project->id,
            'status' => 'edited'
        ]);

        $response = $this->actingAs($user)->getJson('api/boards/find-by-project/'.$project->id);
        $response->assertForbidden();
    }

    public function testBoardGetByProjectIdUnauthorized()
    {
        User::factory()->create();
        Organization::factory()->create();

        $project = Project::factory()->create();

        BoardTemplate::factory()->create();
        Board::factory()->create();

        $response = $this->getJson('api/boards/find-by-project/'.$project->id);
        $response->assertUnauthorized();
    }
}
