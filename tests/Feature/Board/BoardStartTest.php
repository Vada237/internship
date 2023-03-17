<?php

namespace Tests\Feature\Board;

use App\Models\Board;
use App\Models\BoardTemplate;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardStartTest extends TestCase
{
    public function testStartBoardSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::USER)->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_PARTICIPANT)->first()->id]);

        BoardTemplate::factory()->create();

        $board = Board::factory()->create();
        Task::factory()->create();

        $response = $this->actingAs($user)->patchJson('api/boards/start/' . $board->id);
        $response->assertOk();

        $this->assertDatabaseHas('boards', [
            'id' => $board->id,
            'name' => $board->name,
            'project_id' => $board->project_id,
            'status' => Board::ACTIVE
        ]);
    }

    public function testStartBoardForbidden()
    {

    }

    public function testStartBoardUnauthorized()
    {

    }
}
