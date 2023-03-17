<?php

namespace Tests\Feature\TaskTemplate;

use App\Models\BoardTemplate;
use App\Models\TaskTemplate;
use App\Models\User;
use Tests\TestCase;

class TaskTemplateCreateTest extends TestCase
{
    public function testCreateTaskTemplateSuccess()
    {
        $user = User::factory()->create();
        $board = BoardTemplate::factory()->create();

        $response = $this->actingAs($user)->postJson('api/task-templates', [
            'name' => 'first task template',
            'board_template_id' => $board->id
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('task_templates', [
            'id' => TaskTemplate::where('name', 'first task template')->first()->id,
            'name' => TaskTemplate::where('name', 'first task template')->first()->name,
            'board_template_id' => TaskTemplate::where('name', 'first task template')->first()->board_template_id
        ]);
    }

    public function testCreateTaskTemplateUnprocessableEntity()
    {
        $user = User::factory()->create();
        BoardTemplate::factory()->create();

        $response = $this->actingAs($user)->postJson('api/task-templates', [
            'name' => 'first task template'
        ]);

        $response->assertUnprocessable();
    }

    public function testCreateTaskTemplateUnauthorized()
    {
        User::factory()->create();
        $board = BoardTemplate::factory()->create();

        $response = $this->postJson('api/task-templates', [
            'name' => 'first task template',
            'board_template_id' => $board->id
        ]);

        $response->assertUnauthorized();
    }
}
