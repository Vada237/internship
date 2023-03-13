<?php

namespace Tests\Feature\SubtaskTemplate;

use App\Models\Attribute;
use App\Models\BoardTemplate;
use App\Models\SubtaskTemplate;
use App\Models\TaskTemplate;
use App\Models\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubtaskTemplateAddAttributeTest extends TestCase
{
    public function testSubtaskTemplateAddAttributeSuccess()
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

        $subtaskTemplate = SubtaskTemplate::create([
            'name' => 'subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $deadline = new DateTime();

        $response = $this->actingAs($user)->postJson("api/subtask_templates/$subtaskTemplate->id/attributes", [
            'attribute_id' => Attribute::where('name', 'Deadline')->first()->id,
            'value' => $deadline->format('Y-m-d H:i:s')
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('subtask_template_attributes', [
            'subtask_template_id' => $subtaskTemplate->id,
            'attribute_id' => Attribute::where('name', 'Deadline')->first()->id,
            'value' => $deadline
        ]);
    }

    public function testSubtaskTemplateAddAttributeForbidden()
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

        $subtaskTemplate = SubtaskTemplate::create([
            'name' => 'subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $deadline = new DateTime();

        $response = $this->actingAs($anotherUser)->postJson("api/subtask_templates/$subtaskTemplate->id/attributes", [
            'attribute_id' => Attribute::where('name', 'Deadline')->first()->id,
            'value' => $deadline->format('Y-m-d H:i:s')
        ]);

        $response->assertForbidden();
    }

    public function testSubtaskTemplateAddAttributeUnprocessableEntity()
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

        $subtaskTemplate = SubtaskTemplate::create([
            'name' => 'subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $response = $this->actingAs($user)->postJson("api/subtask_templates/$subtaskTemplate->id/attributes", [
            'attribute_id' => Attribute::where('name', 'Deadline')->first()->id
        ]);
        $response->assertUnprocessable();
    }
}
