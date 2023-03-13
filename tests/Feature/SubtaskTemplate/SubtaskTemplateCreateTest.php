<?php

namespace Tests\Feature\SubtaskTemplate;

use App\Models\BoardTemplate;
use App\Models\SubtaskTemplate;
use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubtaskTemplateCreateTest extends TestCase
{
    public function testSubtaskTemplateCreateSuccess()
    {
        $this->seed();

        $user = User::factory()->create();

        $boardTemplate = BoardTemplate::create([
            'name' => 'first board',
            'user_id' => $user->id
        ]);

        $taskTemplate = TaskTemplate::create([
            'name' => 'first task',
            'board_template_id' => $boardTemplate->id
        ]);

        $response = $this->actingAs($user)->postJson('api/subtask_templates', [
            'name' => 'first subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $this->assertDatabaseHas('subtask_templates', [
            'id' => SubtaskTemplate::where('name', 'first subtask')->first()->id,
            'name' => SubtaskTemplate::where('name', 'first subtask')->first()->name,
            'task_template_id' => SubtaskTemplate::where('name', 'first subtask')->first()->task_template_id
        ]);

        $response->assertCreated();
    }

    public function testSubtaskTemplateCreateForbidden()
    {
        $this->seed();

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


        $response = $this->actingAs($anotherUser)->postJson('api/subtask_templates', [
            'name' => 'first subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $response->assertForbidden();
    }

    public function testSubtaskTemplateCreateUnauthorized()
    {
        $this->seed();

        $boardTemplate = BoardTemplate::factory()->create();

        $taskTemplate = TaskTemplate::create([
            'name' => 'first task',
            'board_template_id' => $boardTemplate->id
        ]);

        $response = $this->postJson('api/subtask_templates', [
            'name' => 'first subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $response->assertUnauthorized();
    }
}
