<?php

namespace Tests\Feature\Api\v1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $user = User::create(['email'=>'client@test.com']);
        $token = $user->createToken('token123')->plainTextToken;
        $response = $this->post('/api/v1/logout',[],['Accept'=>'application/json','Authorization'=>'Bearer '.$token]);
      
        $response->assertStatus(200);
        $response->assertExactJson(['Logged out']);
        $this->assertEquals(0,count($user->tokens));
    }
}
