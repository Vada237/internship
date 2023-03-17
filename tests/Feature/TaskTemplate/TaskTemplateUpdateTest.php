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
        $user = User::factory()->create();

        $boardTemplate = BoardTemplate::create([
            'name' => 'board',
            'user_id' => $user->id
        ]);

        $taskTemplate = TaskTemplate::create([
            'name' => 'task',
            'board_template_id' => $boardTemplate->id
        ]);

        $response = $this->actingAs($user)->patchJson('api/task-templates/'.$taskTemplate->id, [
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
        $creater = User::factory()->create();
        $anotherUser = User::factory()->create();

        BoardTemplate::create([
            'name' => 'board template',
            'user_id' => $creater->id
        ]);
        $taskTemplate = TaskTemplate::factory()->create();

        $response = $this->actingAs($anotherUser)->patchJson('api/task-templates/'.$taskTemplate->id, [
            'name' => 'updated title task template'
        ]);

        $response->assertForbidden();
    }

    public function testTaskTemplateUpdateUnauthorized()
    {
        User::factory()->create();
        BoardTemplate::factory()->create();
        $taskTemplate = TaskTemplate::factory()->create();

        $response = $this->patchJson('api/task-templates/'.$taskTemplate->id, [
            'name' => 'updated title task template'
        ]);

        $response->assertUnauthorized();
    }
}
