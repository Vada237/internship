<?php

namespace Tests\Feature\Task;

use App\Models\Attribute;
use App\Models\Board;
use App\Models\BoardTemplate;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskTemplate;
use App\Models\User;
use Tests\TestCase;

class TaskDeleteTest extends TestCase
{
    public function testTaskDeleteSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::ORGANIZATION_SUPERVISOR)->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_SUPERVISOR)->first()->id]);

        BoardTemplate::factory()->create();
        Board::factory()->create();
        $task = Task::factory()->create();
        $subtasks = Subtask::factory()->count(2)->create();

        $subtasks[0]->attributes()->attach($subtasks[0], [
            'attribute_id' => Attribute::where('name', TaskTemplate::DESCRIPTION)->first()->id,
            'value' => 'First subtask description'
        ]);

        $subtasks[1]->attributes()->attach($subtasks[1], [
            'attribute_id' => Attribute::where('name', TaskTemplate::DESCRIPTION)->first()->id,
            'value' => 'second subtask description'
        ]);

        $response = $this->actingAs($user)->deleteJson('api/tasks/'.$task->id);

        $response->assertOk();
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
            'name' => $task->name,
            'board_id' => $task->board_id,
        ]);

        $this->assertDatabaseMissing('subtasks', [
            'id' => $subtasks[0]->id,
            'name' => $subtasks[0]->name,
            'task_id' => $subtasks[0]->task_id
        ]);

        $this->assertDatabaseMissing('subtasks', [
            'id' => $subtasks[1]->id,
            'name' => $subtasks[1]->name,
            'task_id' => $subtasks[1]->task_id
        ]);
    }

    public function testTaskDeleteForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::EMPLOYEE)->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_EXECUTOR)->first()->id]);

        BoardTemplate::factory()->create();
        Board::factory()->create();
        $task = Task::factory()->create();
        $subtasks = Subtask::factory()->count(2)->create();

        $subtasks[0]->attributes()->attach($subtasks[0], [
            'attribute_id' => Attribute::where('name', TaskTemplate::DESCRIPTION)->first()->id,
            'value' => 'First subtask description'
        ]);

        $subtasks[1]->attributes()->attach($subtasks[1], [
            'attribute_id' => Attribute::where('name', TaskTemplate::DESCRIPTION)->first()->id,
            'value' => 'second subtask description'
        ]);

        $response = $this->actingAs($user)->deleteJson('api/tasks/'.$task->id);

        $response->assertForbidden();
    }

    public function testTaskDeleteUnauthorized()
    {
        User::factory()->create();
        Organization::factory()->create();
        Project::factory()->create();

        BoardTemplate::factory()->create();
        Board::factory()->create();
        $task = Task::factory()->create();
        $subtasks = Subtask::factory()->count(2)->create();

        $subtasks[0]->attributes()->attach($subtasks[0], [
            'attribute_id' => Attribute::where('name', TaskTemplate::DESCRIPTION)->first()->id,
            'value' => 'First subtask description'
        ]);

        $subtasks[1]->attributes()->attach($subtasks[1], [
            'attribute_id' => Attribute::where('name', TaskTemplate::DESCRIPTION)->first()->id,
            'value' => 'second subtask description'
        ]);

        $response = $this->deleteJson('api/tasks/'.$task->id);

        $response->assertUnauthorized();
    }
}
