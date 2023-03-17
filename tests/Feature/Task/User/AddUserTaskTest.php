<?php

namespace Tests\Feature\Task\User;

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

class AddUserTaskTest extends TestCase
{
    public function testAddUserTaskSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();
        $invitedUser = User::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::ORGANIZATION_SUPERVISOR)->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_SUPERVISOR)->first()->id]);

        $invitedUser->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::USER)->first()->id]);

        $invitedUser->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_EXECUTOR)->first()->id]);

        $board = Board::create([
            'name' => 'board',
            'project_id' => $project->id,
            'status' => Board::EDITED
        ]);

        $task = Task::create([
            'name' => 'task',
            'board_id' => $board->id,
            'status' => Task::EDITED
        ]);

        $response = $this->actingAs($user)->postJson('api/tasks/users', [
            'user_id' => $invitedUser->id,
            'task_id' => $task->id
        ]);
        $response->assertOk();
        $this->assertDatabaseHas('user_tasks', [
            'user_id' => $invitedUser->id,
            'task_id' => $task->id
        ]);
    }

    public function testAddUserTaskForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();
        $invitedUser = User::factory()->create();

        $invitedUser->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::USER)->first()->id]);

        $invitedUser->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_EXECUTOR)->first()->id]);

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::EMPLOYEE)->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_PARTICIPANT)->first()->id]);

        $board = Board::create([
            'name' => 'board',
            'project_id' => $project->id,
            'status' => Board::EDITED
        ]);

        $task = Task::create([
            'name' => 'task',
            'board_id' => $board->id,
            'status' => Task::EDITED
        ]);

        $response = $this->actingAs($user)->postJson('api/tasks/users', [
            'user_id' => $invitedUser->id,
            'task_id' => $task->id
        ]);

        $response->assertForbidden();
    }

    public function testAddUserTaskUnauthorized()
    {
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();
        $invitedUser = User::factory()->create();

        $invitedUser->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::USER)->first()->id]);

        $invitedUser->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_EXECUTOR)->first()->id]);

        $board = Board::create([
            'name' => 'board',
            'project_id' => $project->id,
            'status' => Board::EDITED
        ]);

        $task = Task::create([
            'name' => 'task',
            'board_id' => $board->id,
            'status' => Task::EDITED
        ]);

        $response = $this->postJson('api/tasks/users', [
            'user_id' => $invitedUser->id,
            'task_id' => $task->id,
        ]);

        $response->assertUnauthorized();
    }
}
