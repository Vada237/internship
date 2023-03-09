<?php

namespace Tests\Feature\BoardTemplate;

use App\Models\BoardTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardTemplateUpdateTest extends TestCase
{
    public function testBoardTemplateUpdateSuccess()
    {
        $this->seed();

        $user = User::first();
        $boardTemplate = BoardTemplate::first();

        $response = $this->actingAs($user)->putJson("api/board_templates/$boardTemplate->id", [
            'name' => 'renamed board'
        ]);

        $response->assertOk();

        $response->assertExactJson([
            'data' => [
                'id' => BoardTemplate::where('name', 'renamed board')->first()->id,
                'name' => BoardTemplate::where('name', 'renamed board')->first()->name
            ]
        ]);
    }

    public function testBoardTemplateUpdateNotFound()
    {
        $this->seed();

        $user = User::first();
        $notExistBoardTemplateId = BoardTemplate::orderBy('id', 'DESC')->first()->id + 1;

        $response = $this->actingAs($user)->putJson("api/board_templates/$notExistBoardTemplateId", [
            'name' => 'renamed board'
        ]);

        $response->assertNotFound();
    }

    public function testBoardTemplateUpdateUnauthorized()
    {
        $this->seed();

        $boardTemplate = BoardTemplate::first();

        $response = $this->putJson("api/board_templates/$boardTemplate->id", [
            'name' => 'renamed board'
        ]);

        $response->assertUnauthorized();
    }

    public function testBoardTemplateUpdateUnprocessableEntity()
    {
        $this->seed();

        $user = User::first();
        $boardTemplate = BoardTemplate::first();

        $response = $this->actingAs($user)->putJson("api/board_templates/$boardTemplate->id");

        $response->assertUnprocessable();
    }
}
