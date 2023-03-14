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
        $user = User::factory()->create();
        $boardTemplate = BoardTemplate::factory()->create();

        $response = $this->actingAs($user)->getJson("api/board-templates/$boardTemplate->id");

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
        $user = User::factory()->create();
        BoardTemplate::factory()->create();
        $notExistBoardTemplateId = BoardTemplate::orderBy('id', 'DESC')->first()->id + 1;

        $response = $this->actingAs($user)->getJson("api/board-templates/$notExistBoardTemplateId");

        $response->assertNotFound();
    }

    public function testBoardTemplateGetByIdUnauthorized()
    {
        User::factory()->create();
        $boardTemplate = BoardTemplate::factory()->create();

        $response = $this->getJson("api/board-templates/$boardTemplate->id");

        $response->assertUnauthorized();
    }
}
