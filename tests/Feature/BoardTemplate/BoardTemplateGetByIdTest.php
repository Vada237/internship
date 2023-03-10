<?php

namespace Tests\Feature\BoardTemplate;

use App\Models\BoardTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardTemplateGetByIdTest extends TestCase
{
    public function testBoardTemplateGetByIdSuccess()
    {
        $this->seed();

        $user = User::factory()->create();
        $boardTemplate = BoardTemplate::first();

        $response = $this->actingAs($user)->getJson("api/board_templates/$boardTemplate->id");

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                'id' => $boardTemplate->id,
                'name' => $boardTemplate->name,
                'creater' => $boardTemplate->user()->first()->name
            ]
        ]);
    }

    public function testBoardTemplateGetByIdNotFound()
    {
        $this->seed();

        $user = User::factory()->create();
        $notExistBoardTemplateId = BoardTemplate::orderBy('id', 'DESC')->first()->id + 1;

        $response = $this->actingAs($user)->getJson("api/board_templates/$notExistBoardTemplateId");

        $response->assertNotFound();
    }

    public function testBoardTemplateGetByIdUnauthorized()
    {
        $this->seed();

        $boardTemplate = BoardTemplate::first();

        $response = $this->getJson("api/board_templates/$boardTemplate->id");

        $response->assertUnauthorized();
    }
}
