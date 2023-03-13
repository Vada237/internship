<?php

namespace Tests\Feature\SubtaskTemplate;

use App\Models\BoardTemplate;
use App\Models\SubtaskTemplate;
use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubtaskTemplateUpdateTest extends TestCase
{
    public function testSubtaskTemplateUpdateSuccess()
    {
        $this->seed();

        $user = User::factory()->create();

        $boardTemplate = BoardTemplate::create([
            'name' => 'first board',
            'user_id' => $user->id
        ]);

        $firstTaskTemplate = TaskTemplate::create([
            'name' => 'first task',
            'board_template_id' => $boardTemplate->id
        ]);

        $secondTaskTemplate = TaskTemplate::create([
            'name' => 'another task',
            'board_template_id' => $boardTemplate->id
        ]);

        $subtaskTemplate = SubtaskTemplate::create([
            'name' => 'first subtask',
            'task_template_id' => $firstTaskTemplate->id
        ]);

        $response = $this->actingAs($user)->putJson('api/subtask-templates/' . $subtaskTemplate->id, [
            'name' => 'edited first subtask',
            'task_template_id' => $secondTaskTemplate->id
        ]);

        $this->assertDatabaseHas('subtask_templates', [
            'id' => SubtaskTemplate::where('name', 'edited first subtask')->first()->id,
            'name' => SubtaskTemplate::where('name', 'edited first subtask')->first()->name,
            'task_template_id' => SubtaskTemplate::where('name', 'edited first subtask')->first()->task_template_id
        ]);

        $response->assertOk();
    }

    public function testSubtaskTemplateUpdateForbidden()
    {
        $this->seed();

        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $boardTemplate = BoardTemplate::create([
            'name' => 'first board',
            'user_id' => $user->id
        ]);

        $firstTaskTemplate = TaskTemplate::create([
            'name' => 'first task',
            'board_template_id' => $boardTemplate->id
        ]);

        $secondTaskTemplate = TaskTemplate::create([
            'name' => 'another task',
            'board_template_id' => $boardTemplate->id
        ]);

        $subtaskTemplate = SubtaskTemplate::create([
            'name' => 'first subtask',
            'task_template_id' => $firstTaskTemplate->id
        ]);

        $response = $this->actingAs($anotherUser)->putJson('api/subtask-templates/' . $subtaskTemplate->id, [
            'name' => 'edited first subtask',
            'task_template_id' => $secondTaskTemplate->id
        ]);

        $response->assertForbidden();
    }

    public function testSubtaskTemplateUpdateUnauthorized()
    {
        $this->seed();

        $TaskTemplate = TaskTemplate::factory()->create();
        $subtaskTemplate = SubtaskTemplate::factory()->create();

        $response = $this->putJson('api/subtask-templates/' . $subtaskTemplate->id, [
            'name' => 'edited first subtask',
            'task_template_id' => $TaskTemplate->id
        ]);

        $response->assertUnauthorized();
    }
}
