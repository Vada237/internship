<?php

namespace Tests\Feature\SubtaskTemplate;

use App\Models\BoardTemplate;
use App\Models\SubtaskTemplate;
use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubtaskTemplateDeleteTest extends TestCase
{
    public function testSubtaskTemplateUpdateSuccess()
    {
        $user = User::factory()->create();

        $boardTemplate = BoardTemplate::create([
            'name' => 'first board',
            'user_id' => $user->id
        ]);

        $taskTemplate = TaskTemplate::create([
            'name' => 'first task',
            'board_template_id' => $boardTemplate->id
        ]);

        $subtaskTemplate = SubtaskTemplate::create([
            'name' => 'first subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $response = $this->actingAs($user)->deleteJson('api/subtask-templates/' . $subtaskTemplate->id);

        $this->assertDatabaseMissing('subtask_templates', [
            'id' => $subtaskTemplate->id,
            'name' => $subtaskTemplate->name,
            'task_template_id' => $subtaskTemplate->task_template_id
        ]);

        $response->assertOk();
    }

    public function testSubtaskTemplateUpdateForbidden()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $boardTemplate = BoardTemplate::create([
            'name' => 'first board',
            'user_id' => $user->id
        ]);

        $taskTemplate = TaskTemplate::create([
            'name' => 'first task',
            'board_template_id' => $boardTemplate->id
        ]);

        $subtaskTemplate = SubtaskTemplate::create([
            'name' => 'first subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $response = $this->actingAs($anotherUser)->deleteJson('api/subtask-templates/' . $subtaskTemplate->id);
        $response->assertForbidden();
    }

    public function testSubtaskTemplateUpdateUnauthorized()
    {
        User::factory()->create();
        BoardTemplate::factory()->create();
        TaskTemplate::factory()->create();

        $subtaskTemplate = SubtaskTemplate::factory()->create();

        $response = $this->deleteJson('api/subtask-templates/' . $subtaskTemplate->id);

        $response->assertUnauthorized();
    }
}
