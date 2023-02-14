<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_get_all_users()
    {
        $users = User::factory()->count(5)->create();

        $response = $this->actingAs($users[0])->get('api/users');
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

        $find_user = User::create([
            'id' => '1',
            'name' => 'denis',
            'password' => 'denis123',
            'email' => 'email'
        ]);

        $response = $this->actingAs($user)->get('api/users', ['id' => '1']);
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

    public function test_get_user_by_name()
    {
        $user = User::factory()->create();

        $find_user = User::create([
            'id' => '1',
            'name' => 'denis',
            'password' => 'denis123',
            'email' => 'email'
        ]);

        $response = $this->actingAs($user)->get('api/users', ['name' => 'denis']);
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
}
