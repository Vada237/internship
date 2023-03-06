<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNewUserCanRegister()
    {
        $response = $this->post('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'testpass'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'name',
            'email'
        ]);
    }

    public function testNewUserCanRegisterWithoutPassword()
    {
        $response = $this->post('api/auth/register', [
            'name' => 'Test user',
            'email' => 'test@example2.com'
        ]);

        $response->assertStatus(422);
    }

    public function testNewUserCanRegisterWithShortEmail() {
        $response = $this->post('api/auth/register', [
            'name' => 'Test user',
            'email' => 't@m.r',
            'password' => 'testpass'
        ]);

        $response->assertStatus(422);

    }

    public function testLoginUser() {
        $user = User::factory()->create();

        $response = $this->post('api/auth/login', [
            'email' => $user->email,
            'password' => $user->password
        ]);
        $response->assertStatus(200);
    }
}
