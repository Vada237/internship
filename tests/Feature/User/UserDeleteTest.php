<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserDeleteTest extends TestCase
{

    public function testDeleteYourselfAvatarWithValidation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch("api/users/$user->id", ["name" => "denis", "avatar" => null]);
        $response->assertOk();
    }

    public function testDeleteYourself()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);

        $this->actingAs($user)->delete("api/users/$user->id");

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    public function testDeleteUserWithoutPermission()
    {
        $this->seed();
        $user = User::factory()->create();
        $deleted_user = User::skip(1)->first();
        $response = $this->actingAs($user)->delete("api/users/$deleted_user->id");
        $response->assertForbidden();
    }

}
