<?php

namespace Tests\Feature\Board;

use App\Models\Board;
use App\Models\BoardTemplate;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class BoardDeleteTest extends TestCase
{
    public function testBoardDeleteSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::USER)->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_SUPERVISOR)->first()->id]);

        BoardTemplate::factory()->create();
        $board = Board::factory()->create();

        $response = $this->actingAs($user)->deleteJson("api/boards/$board->id");

        $response->assertOk();
        $this->assertDatabaseMissing('boards', [
            'id' => $board->id,
            'name' => $board->name,
            'project_id' => $board->project_id,
            'status' => $board->status
        ]);
    }

    public function testBoardDeleteForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::USER)->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_EXECUTOR)->first()->id]);

        BoardTemplate::factory()->create();$board = Board::factory()->create();

        $response = $this->actingAs($user)->deleteJson("api/boards/$board->id");
        $response->assertForbidden();
    }

    public function testBoardDeleteUnauthorized()
    {
        User::factory()->create();
        Organization::factory()->create();
        Project::factory()->create();
        BoardTemplate::factory()->create();

        $board = Board::factory()->create();

        $response = $this->deleteJson("api/boards/$board->id");
        $response->assertUnauthorized();
    }
}
