<?php

namespace Tests\Feature\Api\v1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        User::create(['email'=>'client@test.com']);
        $response = $this->post('api/v1/login',['email'=>'client@test.com'],['Accept'=>'application/json']);

        $response->assertStatus(200);

        $response->assertJsonStructure(['user'=>['email'],'token']);
        
        $email = $response->json('user.email');
        $this->assertEquals('client@test.com',$email);

        $token = $response->json('token');
        $this->assertEquals('42',strlen($token));
    }

    public function test_wrong_credentials()
    {
        $response = $this->post('api/v1/login',['email'=>'user@test.com'],['Accept'=>'application/json']);

        $response->assertStatus(401);
        $response->assertExactJson(['Bad credentials']);
    
    }

    public function test_no_credentials_given()
    {
        $response = $this->post('api/v1/login',[],['Accept'=>'application/json']);

        $response->assertStatus(422);
        $response->assertInvalid('email');
    
    }
}
