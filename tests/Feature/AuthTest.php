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
    public function test_new_user_can_register()
    {
        $response = $this->post('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'testpass'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'name',
            'email',
            'avatar'
        ]);
    }

    public function test_new_user_can_register_without_password()
    {
        $response = $this->post('api/auth/register', [
            'name' => 'Test user',
            'email' => 'test@example2.com'
        ]);

        $response->assertStatus(422);
    }

    public function test_new_user_can_register_with_short_email() {
        $response = $this->post('api/auth/register', [
            'name' => 'Test user',
            'email' => 't@m.r',
            'password' => 'testpass'
        ]);

        $response->assertStatus(422);

    }

    public function test_login_user() {
        $response = $this->post('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'testpass'
        ]);

        $response = $this->post('api/auth/login', [
          "email" => "test@example.com",
          "password" => "testpass"
        ]);

        $this->assertAuthenticated();
        $response->assertStatus(200);
    }
}
