<?php

namespace Tests\Feature\TaskTemplate;

use App\Models\BoardTemplate;
use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTemplateDeleteTest extends TestCase
{
    public function testTaskTemplateDeleteSuccess()
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

        $response = $this->actingAs($user)->deleteJson("api/task-templates/$taskTemplate->id");

        $this->assertDatabaseMissing('task_templates', [
            'id' => $taskTemplate->id,
            'name' => $taskTemplate->name,
            'board_template_id' => $taskTemplate->board_template_id
        ]);

        $response->assertOk();
    }

    public function testTaskTemplateDeleteForbidden()
    {
        $this->seed();

        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $boardTemplate = BoardTemplate::create([
            'name' => 'board',
            'user_id' => $user->id
        ]);

        $taskTemplate = TaskTemplate::create([
            'name' => 'task',
            'board_template_id' => $boardTemplate->id
        ]);

        $response = $this->actingAs($anotherUser)->deleteJson("api/task-templates/$taskTemplate->id");

        $response->assertForbidden();
    }

    public function testTaskTemplateDeleteUnauthorized()
    {
        $this->seed();

        $taskTemplate = TaskTemplate::factory()->create();

        $response = $this->deleteJson("api/task-templates/$taskTemplate->id");

        $response->assertUnauthorized();
    }
}
