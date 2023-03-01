<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserGetTest extends TestCase
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
}
