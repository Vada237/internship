<?php

namespace Tests\Feature\BoardTemplate;

use App\Models\BoardTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function Symfony\Component\String\b;

class BoardTemplateDeleteTest extends TestCase
{
    public function testBoardTemplateDeleteSuccess()
    {
        $user = User::factory()->create();
        $boardTemplate = BoardTemplate::create([
            'name' => 'board template',
            'user_id' => $user->id
        ]);

        $this->assertDatabaseHas('board_templates', [
            'id' => $boardTemplate->id,
            'name' => $boardTemplate->name,
            'user_id' => $boardTemplate->user_id
        ]);

        $response = $this->actingAs($user)->deleteJson("api/board-templates/$boardTemplate->id");

        $this->assertDatabaseMissing('board_templates', [
            'id' => $boardTemplate->id,
            'name' => $boardTemplate->name,
            'user_id' => $boardTemplate->user_id
        ]);
        $response->assertOk();
    }

    public function testBoardTemplateDeleteNotFound()
    {
        $user = User::factory()->create();
        BoardTemplate::factory()->create();

        $notExistBoardTemplateId = BoardTemplate::orderBy('id', 'DESC')->first()->id + 1;

        $response = $this->actingAs($user)->deleteJson("api/board-templates/$notExistBoardTemplateId");

        $response->assertNotFound();
    }

    public function testBoardTemplateDeleteUnauthorized()
    {
        User::factory()->create();
        $boardTemplate = BoardTemplate::factory()->create();

        $response = $this->deleteJson("api/board-templates/$boardTemplate->id");

        $response->assertUnauthorized();
    }
}
