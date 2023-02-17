<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testGetAllUsers()
    {
        $users = User::factory()->count(5)->create();

        $response = $this->actingAs($users[0])->get('api/users?limit=3&offset=0');

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

    public function testGetUserById()
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

    public function testUpdateUserNameWithValidation()
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

    public function testUpdateUserNameWithoutValidation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch("api/users", ["name" => "d"]);
        $response->assertStatus(422);
    }

    public function testUpdateAvatarWithValidation() {

        Storage::fake('avatars');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->json('PATCH', '/api/users', [
            'name' => $user->name,
            'avatar' => $file
        ]);

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

    public function testUpdateAvatarWithoutValidation()
    {

        Storage::fake('avatars');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('avatar.pdf');

        $response = $this->actingAs($user)->json('PATCH', '/api/users', [
            'name' => $user->name,
            'avatar' => $file
        ]);

        $response->assertStatus(422);
    }
    public function testDeleteUserAvatarWithValidation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch("api/users", ["name" => "denis","avatar" => null]);
        $response->assertStatus(200);
    }

    public function  testDeleteUser() {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);

        $this->actingAs($user)->delete("api/users/$user->id");

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }
}
