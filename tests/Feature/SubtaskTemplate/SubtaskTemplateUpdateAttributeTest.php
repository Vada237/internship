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

class SubtaskTemplateUpdateAttributeTest extends TestCase
{
    public function testSubtaskTemplateUpdateAttributeSuccess()
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

        $subtaskTemplate = SubtaskTemplate::create([
            'name' => 'subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $attribute = Attribute::where('name', 'Description')->first();

        $subtaskTemplate->attributes()->attach($attribute->id, [
            'value' => 'first subtask description'
        ]);

        $response = $this->actingAs($user)
            ->putJson("api/subtask-templates/$subtaskTemplate->id/attributes/$attribute->id", [
                'attribute_id' => $attribute->id,
                'value' => 'second subtask description'
            ]);

        $response->assertOk();

        $this->assertDatabaseHas('subtask_template_attributes', [
            'subtask_template_id' => $subtaskTemplate->id,
            'attribute_id' => $attribute->id,
            'value' => 'second subtask description'
        ]);
    }

    public function testSubtaskTemplateUpdateAttributeForbidden()
    {
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

        $attribute = Attribute::where('name', 'Description')->first();

        $subtaskTemplate->attributes()->attach($attribute->id, [
            'value' => 'first subtask description'
        ]);

        $response = $this->actingAs($anotherUser)
            ->putJson("api/subtask-templates/$subtaskTemplate->id/attributes/$attribute->id", [
                'attribute_id' => $attribute->id,
                'value' => 'second subtask description'
            ]);

        $response->assertForbidden();
    }

    public function testSubtaskTemplateUpdateAttributeUnprocessableEntity()
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

        $subtaskTemplate = SubtaskTemplate::create([
            'name' => 'subtask',
            'task_template_id' => $taskTemplate->id
        ]);

        $attribute = Attribute::where('name', 'Description')->first();

        $response = $this->actingAs($user)
            ->putJson("api/subtask-templates/$subtaskTemplate->id/attributes/$attribute->id", [
                'attribute_id' => $attribute->id
            ]);
        $response->assertUnprocessable();
    }
}
