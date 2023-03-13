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
        $this->seed();

        $boardTemplate = BoardTemplate::first();

        $this->assertDatabaseHas('board_templates', [
            'id' => $boardTemplate->id,
            'name' => $boardTemplate->name
        ]);

        $response = $this->actingAs(User::first())->deleteJson("api/board-templates/$boardTemplate->id");


        $this->assertDatabaseMissing('board_templates', [
            'id' => $boardTemplate->id,
            'name' => $boardTemplate->name
        ]);
        $response->assertOk();
    }

    public function testBoardTemplateDeleteNotFound()
    {
        $this->seed();

        $user = User::first();
        $notExistBoardTemplateId = BoardTemplate::orderBy('id', 'DESC')->first()->id + 1;

        $response = $this->actingAs($user)->deleteJson("api/board-templates/$notExistBoardTemplateId");

        $response->assertNotFound();
    }

    public function testBoardTemplateDeleteUnathorized()
    {
        $this->seed();

        $boardTemplate = BoardTemplate::first();

        $response = $this->deleteJson("api/board-templates/$boardTemplate");

        $response->assertUnauthorized();
    }
}
