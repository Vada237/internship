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

class TaskGetByIdTest extends TestCase
{
    public function testTaskGetByIdSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::USER)->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_PARTICIPANT)->first()->id]);

        BoardTemplate::factory()->create();

        Board::factory()->create();
        $task = Task::factory()->create();

        $firstSubtask = Subtask::create([
            'name' => 'subtask 1',
            'task_id' => $task->id
        ]);

        $secondSubtask = Subtask::create([
            'name' => 'subtask 2',
            'task_id' => $task->id
        ]);

        $firstSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 1']);

        $secondSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 2']);

        $response = $this->actingAs($user)->getJson('api/tasks/' . $task->id);

        $response->assertExactJson([
            'data' => [
                'id' => $task->id,
                'name' => $task->name,
                'board_id' => $task->board_id,
                'subtasks' => [
                    [
                        'id' => $firstSubtask->id,
                        'name' => $firstSubtask->name,
                        'task_id' => $firstSubtask->task_id,
                        'attributes' => [
                            [
                                'name' => $firstSubtask->attributes()
                                    ->where('name', TaskTemplate::DESCRIPTION)->first()->name,
                                'value' => $firstSubtask->attributes()
                                    ->where('name', TaskTemplate::DESCRIPTION)->first()->pivot->value
                            ]
                        ]
                    ],
                    [
                        'id' => $secondSubtask->id,
                        'name' => $secondSubtask->name,
                        'task_id' => $secondSubtask->task_id,
                        'attributes' => [
                            [
                                'name' => $secondSubtask->attributes()
                                    ->where('name', TaskTemplate::DESCRIPTION)->first()->name,
                                'value' => $secondSubtask->attributes()
                                    ->where('name', TaskTemplate::DESCRIPTION)->first()->pivot->value
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $response->assertOk();
    }

    public function testTaskGetByIdForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();
        $anotherProject = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::USER)->first()->id]);

        $user->projects()->attach($anotherProject->id,
            ['role_id' => Role::byName(Role::PROJECT_PARTICIPANT)->first()->id]);

        BoardTemplate::factory()->create();

        Board::create([
            'name' => 'board',
            'project_id' => $project->id,
            'status' => Board::EDITED
        ]);

        $task = Task::factory()->create();

        $firstSubtask = Subtask::create([
            'name' => 'subtask 1',
            'task_id' => $task->id
        ]);

        $secondSubtask = Subtask::create([
            'name' => 'subtask 2',
            'task_id' => $task->id
        ]);

        $firstSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 1']);

        $secondSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 2']);

        $response = $this->actingAs($user)->getJson('api/tasks/' . $task->id);
        $response->assertForbidden();
    }

    public function testTaskGetByIdUnauthorized()
    {
        User::factory()->create();
        Organization::factory()->create();
        $project = Project::factory()->create();

        BoardTemplate::factory()->create();

        Board::create([
            'name' => 'board',
            'project_id' => $project->id,
            'status' => Board::EDITED
        ]);

        $task = Task::factory()->create();

        $firstSubtask = Subtask::create([
            'name' => 'subtask 1',
            'task_id' => $task->id
        ]);

        $secondSubtask = Subtask::create([
            'name' => 'subtask 2',
            'task_id' => $task->id
        ]);

        $firstSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 1']);

        $secondSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 2']);

        $response = $this->getJson('api/tasks/' . $task->id);
        $response->assertUnauthorized();
    }
}
