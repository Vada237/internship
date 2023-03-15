<?php

namespace Tests\Feature\Board;


use App\Models\Board;
use App\Models\BoardTemplate;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class BoardCreateTest extends TestCase
{
    public function testBoardCreateSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        $boardTemplate = BoardTemplate::factory()->create();

        $response = $this->actingAs($user)->postJson('api/boards', [
            'board_template_id' => $boardTemplate->id,
            'project_id' => $project->id
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('boards', [
            'id' => Board::where('name', $boardTemplate->name)->first()->id,
            'name' => Board::where('name', $boardTemplate->name)->first()->name,
            'project_id' => Board::where('name', $boardTemplate->name)->first()->project_id
         ]);
    }

    public function testBoardCreateForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['USER'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);

        $boardTemplate = BoardTemplate::factory()->create();

        $response = $this->actingAs($user)->postJson('api/boards', [
            'board_template_id' => $boardTemplate->id,
            'project_id' => $project->id
        ]);

        $response->assertForbidden();
    }

    public function testBoardCreateUnauthorized()
    {
        User::factory()->create();
        Organization::factory()->create();
        Project::factory()->create();
        BoardTemplate::factory()->create();

        $response = $this->postJson('api/boards', [
            'board_template_id' => BoardTemplate::first()->id,
            'project_id' => Project::first()->id
        ]);

        $response->assertUnauthorized();
    }
}
