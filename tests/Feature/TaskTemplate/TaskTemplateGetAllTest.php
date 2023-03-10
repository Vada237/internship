<?php

namespace Tests\Feature\TaskTemplate;

use App\Models\BoardTemplate;
use App\Models\Task;
use App\Models\TaskTemplate;
use App\Models\User;
use Database\Seeders\Task\TaskTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTemplateGetAllTest extends TestCase
{
    public function testTaskTemplateGetAllSuccess()
    {
        BoardTemplate::factory()->create();
        TaskTemplate::factory()->count(5)->create();
        $user = User::factory()->create();

        $limit = 2;
        $offset = 1;

        $response = $this->actingAs($user)->getJson("api/task_templates?limit=$limit&offset=$offset");

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                [
                    "id" => TaskTemplate::offset($offset)->limit($limit)->first()->id,
                    "name" => TaskTemplate::offset($offset)->limit($limit)->first()->name,
                    "board_template_id" => TaskTemplate::offset($offset)->limit($limit)->first()->board_template_id
                ],
                [
                    "id" => TaskTemplate::offset($offset + 1)->limit($limit)->first()->id,
                    "name" => TaskTemplate::offset($offset + 1)->limit($limit)->first()->name,
                    "board_template_id" => TaskTemplate::offset($offset + 1)->limit($limit)->first()->board_template_id
                ]
            ]
        ]);
    }

    public function testTaskTemplateGetAllUnauthorized()
    {
        BoardTemplate::factory()->create();
        TaskTemplate::factory()->count(5)->create();

        $limit = 2;
        $offset = 1;

        $response = $this->getJson("api/task_templates?limit=$limit&offset=$offset");

        $response->assertUnauthorized();
    }
}
