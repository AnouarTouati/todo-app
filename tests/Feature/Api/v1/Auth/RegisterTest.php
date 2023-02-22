<?php

namespace Tests\Feature\Api\v1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register()
    {
        $response = $this->post('/api/v1/register', ['email' => 'client@test.com',], ['Accept' => 'application/json']);

        $response->assertStatus(201);

        $response->assertJsonStructure(['user' => ['email'], 'token']);

        $email = $response->json('user.email');
        $this->assertEquals('client@test.com', $email);

        $token = $response->json('token');
        $this->assertEquals('42', strlen($token));
    }

    public function test_email_is_required()
    {
        $response = $this->post('/api/v1/register', [], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertInvalid(['email']);
    }

    public function test_email_is_unique()
    {
        $response = $this->post('/api/v1/register', ['email' => 'client@test.com'], ['Accept' => 'application/json']);
        $response = $this->post('/api/v1/register', ['email' => 'client@test.com'], ['Accept' => 'application/json']);

        $response->assertInvalid('email');
    }
}
