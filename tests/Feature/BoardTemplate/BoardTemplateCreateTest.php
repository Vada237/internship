<?php

namespace Tests\Feature\BoardTemplate;

use App\Models\BoardTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardTemplateCreateTest extends TestCase
{
    public function testCreateBoardTemplateSuccess()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('api/board-templates', [
            'name' => 'first board template'
        ]);

        $this->assertDatabaseHas('board_templates', [
            'id' => BoardTemplate::where('name', 'first board template')->first()->id,
            'name' => BoardTemplate::where('name', 'first board template')->first()->name
        ]);

        $response->assertCreated();
    }

    public function testCreateBoardTemplateUnauthorize()
    {
        $response = $this->postJson('api/board-templates', [
            'name' => 'first board template'
        ]);

        $response->assertUnauthorized();
    }
}
