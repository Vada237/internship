<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthRegisterTest extends TestCase
{
    public function testNewUserCanRegisterSuccess()
    {
        $response = $this->post('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'testpass'
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            'name',
            'email'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
    }

    public function testNewUserCanRegisterWithoutPassword()
    {
        $response = $this->postjson('api/auth/register', [
            'name' => 'Test user',
            'email' => 'test@example2.com'
        ]);

        $response->assertUnprocessable();
    }

    public function testNewUserCanRegisterWithShortEmail()
    {
        $response = $this->postJson('api/auth/register', [
            'name' => 'Test user',
            'email' => 't@m.r',
            'password' => 'testpass'
        ]);

        $response->assertUnprocessable();
    }
}
