<?php

namespace Tests\Feature\SubtaskTemplate;

use App\Models\Attribute;
use App\Models\BoardTemplate;
use App\Models\SubtaskTemplate;
use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubtaskTemplateDeleteAttributeTest extends TestCase
{
    public function testSubtaskTemplateDeleteSuccess()
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

        $subtaskTemplate = SubtaskTemplate::create([
            'name' => 'subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $attribute = Attribute::where('name', 'Description')->first();

        $subtaskTemplate->attributes()->attach($attribute->id, [
            'value' => 'first subtask description'
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("api/subtask-templates/$subtaskTemplate->id/attributes/$attribute->id");

        $this->assertDatabaseMissing('subtask_template_attributes', [
            'subtask_template_id' => $subtaskTemplate->id,
            'attribute_id' => $attribute->id,
            'value' => 'first subtask description'
        ]);

        $response->assertOk();
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

        $subtaskTemplate = SubtaskTemplate::create([
            'name' => 'subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $attribute = Attribute::where('name', 'Description')->first();

        $subtaskTemplate->attributes()->attach($attribute->id, [
            'value' => 'first subtask description'
        ]);

        $response = $this->actingAs($anotherUser)
            ->deleteJson("api/subtask-templates/$subtaskTemplate->id/attributes/$attribute->id");
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

        $subtaskTemplate = SubtaskTemplate::create([
            'name' => 'subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $attribute = Attribute::where('name', 'Description')->first();

        $subtaskTemplate->attributes()->attach($attribute->id, [
            'value' => 'first subtask description'
        ]);

        $response = $this->deleteJson("api/subtask-templates/$subtaskTemplate->id/attributes/$attribute->id");

        $response->assertUnauthorized();
    }
}
