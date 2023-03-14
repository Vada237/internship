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
        $user = User::factory()->create();

        BoardTemplate::create([
            'name' => 'board',
            'user_id' => $user->id
        ]);
        TaskTemplate::factory()->count(5)->create();

        $response = $this->actingAs($user)->getJson('api/task-templates/' . TaskTemplate::first()->id);

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
        $user = User::factory()->create();

        BoardTemplate::create([
            'name' => 'board',
            'user_id' => $user->id
        ]);
        TaskTemplate::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('api/task-templates/' . TaskTemplate::orderBy('id','DESC')->first()->id + 1);

        $response->assertNotFound();
    }

    public function testTaskTemplateGetByIdUnauthorized()
    {
        User::factory()->create();

        BoardTemplate::create([
            'name' => 'board',
            'user_id' => User::first()->id
        ]);
        TaskTemplate::factory()->count(5)->create();

        $response = $this->getJson('api/task-templates/' . TaskTemplate::first()->id + 1);

        $response->assertUnauthorized();
    }
}
