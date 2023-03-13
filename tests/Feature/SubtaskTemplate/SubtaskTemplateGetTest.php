<?php

namespace Tests\Feature\SubtaskTemplate;

use App\Models\BoardTemplate;
use App\Models\SubtaskTemplate;
use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubtaskTemplateGetTest extends TestCase
{
    public function testSubtaskTemplateGetTestSuccess()
    {
        $this->seed();
        $response = $this->actingAs(User::first())->getJson('api/subtask-templates/' . SubtaskTemplate::first()->id);

        $response->assertExactJson([
            'data' => [
                'id' => SubtaskTemplate::first()->id,
                'name' => SubtaskTemplate::first()->name,
                'task_template_id' => SubtaskTemplate::first()->task_template_id
            ]
        ]);

        $response->assertOk();
    }

    public function testSubtaskTemplateGetTestUnauthorized()
    {
        $this->seed();

        $response = $this->getJson('api/subtask-templates/' . SubtaskTemplate::first()->id);

        $response->assertUnauthorized();
    }
}