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

class TaskGetByBoardIdTest extends TestCase
{
    public function testTaskGetByBoardIdSuccess()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::ORGANIZATION_SUPERVISOR)->first()->id]);

        $user->projects()->attach($project->id,
            ['role_id' => Role::byName(Role::PROJECT_SUPERVISOR)->first()->id]);

        BoardTemplate::factory()->create();

        $board = Board::factory()->create();
        $tasks = Task::factory()->count(2)->create();

        $firstSubtask = Subtask::create([
            'name' => 'subtask 1 for task 1',
            'task_id' => $tasks[0]->id
        ]);

        $secondSubtask = Subtask::create([
            'name' => 'subtask 2 for task 1',
            'task_id' => $tasks[0]->id
        ]);

        $thirdSubtask = Subtask::create([
            'name' => 'subtask 1 for task 2',
            'task_id' => $tasks[1]->id
        ]);

        $fourSubtask = Subtask::create([
            'name' => 'subtask 1 for task 2',
            'task_id' => $tasks[1]->id
        ]);

        $firstSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 1']);

        $secondSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 2']);

        $thirdSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 1']);

        $fourSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 2']);

        $response = $this->actingAs($user)->getJson('api/tasks/find-by-board/' . $board->id);
        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                [
                    'id' => $tasks[0]->id,
                    'name' => $tasks[0]->name,
                    'board_id' => $tasks[0]->board_id,
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
                ],
                [
                    'id' => $tasks[1]->id,
                    'name' => $tasks[1]->name,
                    'board_id' => $tasks[1]->board_id,
                    'subtasks' => [
                        [
                            'id' => $thirdSubtask->id,
                            'name' => $thirdSubtask->name,
                            'task_id' => $thirdSubtask->task_id,
                            'attributes' => [
                                [
                                    'name' => $thirdSubtask->attributes()
                                        ->where('name', TaskTemplate::DESCRIPTION)->first()->name,
                                    'value' => $thirdSubtask->attributes()
                                        ->where('name', TaskTemplate::DESCRIPTION)->first()->pivot->value
                                ]
                            ]
                        ],
                        [
                            'id' => $fourSubtask->id,
                            'name' => $fourSubtask->name,
                            'task_id' => $fourSubtask->task_id,
                            'attributes' => [
                                [
                                    'name' => $fourSubtask->attributes()
                                        ->where('name', TaskTemplate::DESCRIPTION)->first()->name,
                                    'value' => $fourSubtask->attributes()
                                        ->where('name', TaskTemplate::DESCRIPTION)->first()->pivot->value
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function testTaskGetByBoardIdForbidden()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $project = Project::factory()->create();
        $anotherProject = Project::factory()->create();

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::EMPLOYEE)->first()->id]);

        $user->projects()->attach($anotherProject->id,
            ['role_id' => Role::byName(Role::PROJECT_PARTICIPANT)->first()->id]);

        BoardTemplate::create([
            'name' => 'board',
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);

        $board = Board::create([
            'name' => 'board',
            'project_id' => $project->id,
            'status' => Board::EDITED
        ]);

        $tasks = Task::factory()->count(2)->create();

        $firstSubtask = Subtask::create([
            'name' => 'subtask 1 for task 1',
            'task_id' => $tasks[0]->id
        ]);

        $secondSubtask = Subtask::create([
            'name' => 'subtask 2 for task 1',
            'task_id' => $tasks[0]->id
        ]);

        $thirdSubtask = Subtask::create([
            'name' => 'subtask 1 for task 2',
            'task_id' => $tasks[1]->id
        ]);

        $fourSubtask = Subtask::create([
            'name' => 'subtask 1 for task 2',
            'task_id' => $tasks[1]->id
        ]);

        $firstSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 1']);

        $secondSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 2']);

        $thirdSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 1']);

        $fourSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 2']);

        $response = $this->actingAs($user)->getJson('api/tasks/find-by-board/' . $board->id);
        $response->assertForbidden();
    }

    public function testTaskGetByBoardIdUnauthorized()
    {
        $user = User::factory()->create();
        Organization::factory()->create();
        $project = Project::factory()->create();

        BoardTemplate::create([
            'name' => 'board',
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);

        $board = Board::factory()->create();
        $tasks = Task::factory()->count(2)->create();

        $firstSubtask = Subtask::create([
            'name' => 'subtask 1 for task 1',
            'task_id' => $tasks[0]->id
        ]);

        $secondSubtask = Subtask::create([
            'name' => 'subtask 2 for task 1',
            'task_id' => $tasks[0]->id
        ]);

        $thirdSubtask = Subtask::create([
            'name' => 'subtask 1 for task 2',
            'task_id' => $tasks[1]->id
        ]);

        $fourSubtask = Subtask::create([
            'name' => 'subtask 1 for task 2',
            'task_id' => $tasks[1]->id
        ]);

        $firstSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 1']);

        $secondSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 2']);

        $thirdSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 1']);

        $fourSubtask->attributes()->attach(Attribute::where('name', TaskTemplate::DESCRIPTION)
            ->first()->id, ['value' => 'description for subtask 2']);

        $response = $this->getJson('api/tasks/find-by-board/' . $board->id);
        $response->assertUnauthorized();
    }
}
