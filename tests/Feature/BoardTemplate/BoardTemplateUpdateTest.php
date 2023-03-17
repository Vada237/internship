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
        $user = User::factory()->create();
        $boardTemplate = BoardTemplate::create([
            'name' => 'board template',
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->patchJson("api/board-templates/$boardTemplate->id", [
            'name' => 'renamed board'
        ]);

        $response->assertOk();

        $response->assertExactJson([
            'data' => [
                'id' => BoardTemplate::where('name', 'renamed board')->first()->id,
                'name' => BoardTemplate::where('name', 'renamed board')->first()->name,
                'creater' => BoardTemplate::where('name', 'renamed board')->first()->user()->first()->name
            ]
        ]);
    }

    public function testBoardTemplateUpdateNotFound()
    {
        $user = User::factory()->create();
        BoardTemplate::factory()->create();

        $notExistBoardTemplateId = BoardTemplate::orderBy('id', 'DESC')->first()->id + 1;

        $response = $this->actingAs($user)->patchJson("api/board-templates/$notExistBoardTemplateId", [
            'name' => 'renamed board'
        ]);

        $response->assertNotFound();
    }

    public function testBoardTemplateUpdateUnauthorized()
    {
        User::factory()->create();
        $boardTemplate = BoardTemplate::factory()->create();

        $response = $this->patchJson("api/board-templates/$boardTemplate->id", [
            'name' => 'renamed board'
        ]);

        $response->assertUnauthorized();
    }

    public function testBoardTemplateUpdateUnprocessableEntity()
    {
        $user = User::factory()->create();
        $boardTemplate = BoardTemplate::factory()->create();

        $response = $this->actingAs($user)->patchJson("api/board-templates/$boardTemplate->id");

        $response->assertUnprocessable();
    }
}
