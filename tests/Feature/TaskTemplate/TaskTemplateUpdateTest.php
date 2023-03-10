<?php

namespace Tests\Feature\TaskTemplate;

use App\Models\BoardTemplate;
use App\Models\TaskTemplate;
use App\Models\User;
use Tests\TestCase;

class TaskTemplateUpdateTest extends TestCase
{
    public function testTaskTemplateUpdateSuccess()
    {
        $this->seed();

        $user = User::factory()->create();

        $boardTemplate = BoardTemplate::create([
            'name' => 'board',
            'user_id' => $user->id
        ]);

        $taskTemplate = TaskTemplate::create([
            'name' => 'task',
            'board_template_id' => $boardTemplate->id
        ]);

        $response = $this->actingAs($user)->patchJson('api/task_templates/'.$taskTemplate->id, [
            'name' => 'updated title task template'
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('task_templates', [
            'id' => TaskTemplate::where('name', 'updated title task template')->first()->id,
            'name' => TaskTemplate::where('name', 'updated title task template')->first()->name,
            'board_template_id' => TaskTemplate::where('name', 'updated title task template')->first()->board_template_id
        ]);
    }

    public function testTaskTemplateUpdateForbidden()
    {
        $this->seed();

        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $taskTemplate = TaskTemplate::factory()->create();

        $response = $this->actingAs($anotherUser)->patchJson('api/task_templates/'.$taskTemplate->id, [
            'name' => 'updated title task template'
        ]);

        $response->assertForbidden();
    }

    public function testTaskTemplateUpdateUnauthorized()
    {
        $this->seed();

        $taskTemplate = TaskTemplate::factory()->create();

        $response = $this->patchJson('api/task_templates/'.$taskTemplate->id, [
            'name' => 'updated title task template'
        ]);

        $response->assertUnauthorized();
    }
}
