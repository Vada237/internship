<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
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
        $this->seed();

        $response = $this->actingAs(User::first())->get('api/users?limit=2&offset=0');

        $response->assertOk();
        $response->assertExactJson([
            "data" => [
                [
                    "id" => User::first()->id,
                    "name" => User::first()->name,
                    "email" => User::first()->email,
                    "avatar" => User::first()->avatar
                ],
                [
                    "id" => User::offset(1)->first()->id,
                    "name" => User::offset(1)->first()->name,
                    "email" => User::offset(1)->first()->email,
                    "avatar" => User::offset(1)->first()->avatar
                ]
            ]
        ]);
    }

    public function testGetUserById()
    {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("api/users/$user->id");
        $response->assertStatus(200);
        $response->assertExactJson([
            "data" => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "avatar" => $user->avatar
            ]
        ]);
    }

    public function testUpdateYourselfNameWithValidation()
    {
        $this->seed();
        $user = User::first();
        $response = $this->actingAs($user)->patch("api/users/$user->id", ["name" => "denis"]);
        $response->assertOk();
        $response->assertExactJson([
            "data" => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "avatar" => $user->avatar
            ]
        ]);
    }


    public function testUpdateYourselfNameWithoutValidation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patchJson("api/users/$user->id", ["name" => "d"]);
        $response->assertUnprocessable();
    }


    public function testUpdateYourselfAvatarWithValidation() {

        Storage::fake('avatars');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->patch("/api/users/$user->id", [
            'name' => $user->name,
            'avatar' => $file
        ]);

        $user = User::where('id', $user->id)->first();

        $response->assertOk();
        $response->assertExactJson([
            "data" => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "avatar" => $user->avatar
            ]
        ]);
    }

    public function testUpdateYourselfAvatarWithoutValidation()
    {

        Storage::fake('avatars');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('avatar.pdf');

        $response = $this->actingAs($user)->json('PATCH', "/api/users/$user->id", [
            'name' => $user->name,
            'avatar' => $file
        ]);

        $response->assertUnprocessable();
    }
    public function testDeleteYourselfAvatarWithValidation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch("api/users/$user->id", ["name" => "denis","avatar" => null]);
        $response->assertOk();
    }

    public function  testDeleteYourself() {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);

        $this->actingAs($user)->delete("api/users/$user->id");

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    public function testDeleteUserWithoutPermission() {
        $this->seed();
        $user = User::factory()->create();
        $deleted_user = User::skip(1)->first();
        $response = $this->actingAs($user)->delete("api/users/$deleted_user->id");
        $response->assertForbidden();
    }

    public function testUpdateUserWithoutPermission() {

        $this->seed();
        $user = User::factory()->create();
        $deleted_user = User::skip(1)->first();

        $response = $this->actingAs($user)->patch("api/users/$deleted_user->id", [
            'name' => 'crushed'
        ]);
        $response->assertForbidden();
    }

}
