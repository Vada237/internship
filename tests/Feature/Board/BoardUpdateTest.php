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

class BoardUpdateTest extends TestCase
{
    public function testUpdateBoardSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['EMPLOYEE'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        $updatedTemplate = BoardTemplate::factory()->create();

        $board = Board::create([
            'name' => 'board 1',
            'project_id' => $project->id,
            'status' => Board::statuses['EDITED']
        ]);

        $response = $this->actingAs($user)->putJson('api/boards/' . $board->id, [
            'board_template_id' => $updatedTemplate->id
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('boards', [
            'id' => Board::where('name', $updatedTemplate->name)->first()->id,
            'name' => Board::where('name', $updatedTemplate->name)->first()->name,
            'project_id' => Board::where('name', $updatedTemplate->name)->first()->project_id,
            'status' => Board::where('name', $updatedTemplate->name)->first()->status,
        ]);
    }

    public function testUpdateBoardForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['EMPLOYEE'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);

        $updatedTemplate = BoardTemplate::create([
            'name' => 'board 2',
            'user_id' => $user->id
        ]);

        $board = Board::create([
            'name' => 'board 1',
            'project_id' => $project->id,
            'status' => Board::statuses['EDITED']
        ]);

        $response = $this->actingAs($user)->putJson('api/boards/' . $board->id, [
            'board_template_id' => $updatedTemplate->id
        ]);

        $response->assertForbidden();
    }

    public function testUpdateActiveBoardForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['EMPLOYEE'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        $updatedTemplate = BoardTemplate::create([
            'name' => 'board 2',
            'user_id' => $user->id
        ]);

        $board = Board::create([
            'name' => 'board 1',
            'project_id' => $project->id,
            'status' => Board::statuses['ACTIVE']
        ]);

        $response = $this->actingAs($user)->putJson('api/boards/' . $board->id, [
            'board_template_id' => $updatedTemplate->id
        ]);

        $response->assertForbidden();
    }

    public function testUpdateBoardTemplateUnauthorized()
    {
        $user = User::factory()->create();

        Organization::factory()->create();
        $project = Project::factory()->create();

        $updatedTemplate = BoardTemplate::create([
            'name' => 'board 2',
            'user_id' => $user->id
        ]);

        $board = Board::create([
            'name' => 'board 1',
            'project_id' => $project->id,
            'status' => Board::statuses['EDITED']
        ]);

        $response = $this->putJson('api/boards/' . $board->id, [
            'board_template_id' => $updatedTemplate->id
        ]);

        $response->assertUnauthorized();
    }
}
