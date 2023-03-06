<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
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

    public function testUpdateYourselfAvatarWithValidation()
    {
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

    public function testUpdateUserWithoutPermission()
    {
        $this->seed();
        $user = User::factory()->create();
        $deleted_user = User::skip(1)->first();

        $response = $this->actingAs($user)->patch("api/users/$deleted_user->id", [
            'name' => 'crushed'
        ]);
        $response->assertForbidden();
    }
}
