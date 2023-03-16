<?php

namespace Tests\Feature\Task;

use App\Models\Attribute;
use App\Models\Board;
use App\Models\BoardTemplate;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\Subtask;
use App\Models\SubtaskTemplate;
use App\Models\Task;
use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskCreateTest extends TestCase
{

    public function testTaskCreateSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        BoardTemplate::factory()->create();
        $taskTemplate = TaskTemplate::factory()->create();
        $subtasks = SubtaskTemplate::factory()->count(2)->create();

        $subtasks[0]->attributes()->attach($subtasks[0], [
            'attribute_id' => Attribute::where('name', TaskTemplate::ATTRIBUTES['DESCRIPTION'])->first()->id,
            'value' => 'First subtask description'
        ]);

        $subtasks[1]->attributes()->attach($subtasks[1], [
            'attribute_id' => Attribute::where('name', TaskTemplate::ATTRIBUTES['DESCRIPTION'])->first()->id,
            'value' => 'second subtask description'
        ]);

        $board = Board::factory()->create();

        $response = $this->actingAs($user)->postJson('api/tasks', [
            'board_id' => $board->id,
            'task_template_id' => $taskTemplate->id
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('tasks', [
            'id' => Task::where('name', $taskTemplate->name)->first()->id,
            'name' => Task::where('name', $taskTemplate->name)->first()->name,
            'board_id' => Task::where('name', $taskTemplate->name)->first()->board_id,
        ]);

        $this->assertDatabaseHas('subtasks', [
            'id' => Subtask::where('name', $subtasks[0]->name)->first()->id,
            'name' => Subtask::where('name', $subtasks[0]->name)->first()->name,
            'task_id' => Subtask::where('name', $subtasks[0]->name)->first()->task_id
        ]);

        $this->assertDatabaseHas('subtasks', [
            'id' => Subtask::where('name', $subtasks[1]->name)->first()->id,
            'name' => Subtask::where('name', $subtasks[1]->name)->first()->name,
            'task_id' => Subtask::where('name', $subtasks[1]->name)->first()->task_id
        ]);

        $this->assertDatabaseHas('subtask_attributes', [
            'subtask_id' => Subtask::where('name', $subtasks[0]->name)->first()->attributes()->first()->pivot->subtask_id,
            'attribute_id' => Subtask::where('name', $subtasks[0]->name)->first()->attributes()->first()->pivot->attribute_id,
            'value' => Subtask::where('name', $subtasks[0]->name)->first()->attributes()->first()->pivot->value
        ]);

        $this->assertDatabaseHas('subtask_attributes', [
            'subtask_id' => Subtask::where('name', $subtasks[1]->name)->first()->attributes()->first()->pivot->subtask_id,
            'attribute_id' => Subtask::where('name', $subtasks[1]->name)->first()->attributes()->first()->pivot->attribute_id,
            'value' => Subtask::where('name', $subtasks[1]->name)->first()->attributes()->first()->pivot->value
        ]);
    }

    public function testTaskCreateForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['USER'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);

        BoardTemplate::factory()->create();
        $taskTemplate = TaskTemplate::factory()->create();
        $subtasks = SubtaskTemplate::factory()->count(2)->create();

        $subtasks[0]->attributes()->attach($subtasks[0], [
            'attribute_id' => Attribute::where('name', TaskTemplate::ATTRIBUTES['DESCRIPTION'])->first()->id,
            'value' => 'First subtask description'
        ]);

        $subtasks[1]->attributes()->attach($subtasks[1], [
            'attribute_id' => Attribute::where('name', TaskTemplate::ATTRIBUTES['DESCRIPTION'])->first()->id,
            'value' => 'second subtask description'
        ]);

        $board = Board::factory()->create();

        $response = $this->actingAs($user)->postJson('api/tasks', [
            'board_id' => $board->id,
            'task_template_id' => $taskTemplate->id
        ]);

        $response->assertForbidden();
    }

    public function testTaskCreateUnauthorized()
    {
        User::factory()->create();
        Organization::factory()->create();
        Project::factory()->create();

        BoardTemplate::factory()->create();
        $taskTemplate = TaskTemplate::factory()->create();
        $subtasks = SubtaskTemplate::factory()->count(2)->create();

        $subtasks[0]->attributes()->attach($subtasks[0], [
            'attribute_id' => Attribute::where('name', TaskTemplate::ATTRIBUTES['DESCRIPTION'])->first()->id,
            'value' => 'First subtask description'
        ]);

        $subtasks[1]->attributes()->attach($subtasks[1], [
            'attribute_id' => Attribute::where('name', TaskTemplate::ATTRIBUTES['DESCRIPTION'])->first()->id,
            'value' => 'second subtask description'
        ]);

        $board = Board::factory()->create();

        $response = $this->postJson('api/tasks', [
            'board_id' => $board->id,
            'task_template_id' => $taskTemplate->id
        ]);

        $response->assertUnauthorized();
    }

    public function testTaskCreateUnprocessableEntity()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);

        BoardTemplate::factory()->create();
        $taskTemplate = TaskTemplate::factory()->create();
        $subtasks = SubtaskTemplate::factory()->count(2)->create();

        $subtasks[0]->attributes()->attach($subtasks[0], [
            'attribute_id' => Attribute::where('name', TaskTemplate::ATTRIBUTES['DESCRIPTION'])->first()->id,
            'value' => 'First subtask description'
        ]);

        $subtasks[1]->attributes()->attach($subtasks[1], [
            'attribute_id' => Attribute::where('name', TaskTemplate::ATTRIBUTES['DESCRIPTION'])->first()->id,
            'value' => 'second subtask description'
        ]);

        Board::factory()->create();

        $response = $this->actingAs($user)->postJson('api/tasks', [
            'task_template_id' => $taskTemplate->id
        ]);

        $response->assertUnprocessable();
    }
}
