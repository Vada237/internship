<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_get_all_users()
    {
        $users = User::factory()->count(5)->create();

        $response = $this->actingAs($users[0])->get('api/users/2/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "*" => [
                    "id",
                    "name",
                    "email",
                    "avatar"
                ]
            ]
        ]);
    }

    public function test_get_user_by_id()
    {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("api/users/$user->id");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "id",
                "name",
                "email",
                "avatar"
            ]
        ]);
    }

    public function test_update_user_name_with_validation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch("api/users", ["name" => "denis"]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "id",
                "name",
                "email",
                "avatar"
            ]
        ]);
    }

    public function test_update_user_name_without_validation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch("api/users", ["name" => "d"]);
        $response->assertStatus(422);
    }

    public function test_delete_user_avatar_with_validation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch("api/users", ["name" => "denis","avatar" => null]);
        $response->assertStatus(200);
    }
}
