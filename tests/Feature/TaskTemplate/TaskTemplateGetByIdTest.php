<?php

namespace Tests\Feature\TaskTemplate;

use App\Models\BoardTemplate;
use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTemplateGetByIdTest extends TestCase
{
    public function testTaskTemplateGetByIdSuccess()
    {
        BoardTemplate::factory()->create();
        TaskTemplate::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('api/task_templates/' . TaskTemplate::first()->id);

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                'id' => TaskTemplate::first()->id,
                'name' => TaskTemplate::first()->name,
                'board_template_id' => TaskTemplate::first()->board_template_id
            ]
        ]);
    }

    public function testTaskTemplateGetByIdNotFound()
    {
        BoardTemplate::factory()->create();
        TaskTemplate::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('api/task_templates/' . TaskTemplate::first()->id + 1);

        $response->assertNotFound();
    }

    public function testTaskTemplateGetByIdUnauthorized()
    {
        BoardTemplate::factory()->create();
        TaskTemplate::factory()->create();

        $response = $this->getJson('api/task_templates/' . TaskTemplate::first()->id + 1);

        $response->assertUnauthorized();
    }
}
