<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\RollDice;

class AuthTest extends TestCase
{
    //use WithoutMiddleware;
    /**
     * A basic feature test example.
     */
    public function test_register_funciona(): void
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->make();

        $response = $this->post('/api/players',[
            'name' => $user->name,
            'email' =>  $user->email,
            'password' =>  'password'
        ]);

        $response->assertStatus(200);
        
    }

    public function test_login_funciona()
    {
        $this->withoutExceptionHandling();

        $user = User::get()->last();

        $response = $this->post('/api/login',[
            'email' =>  $user->email,
            'password' =>  'password'
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'message' => 'user loged  successfully'
        ]);
    }

    public function test_logout_funciona() //no funciona :(
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $token = $user->createToken('token')->accessToken;

        $response = $this->withHeaders([
                'Authorization' => 'Bearer' . $token
                ])->post('/api/logout');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'message' => 'user loged  successfully'
        ]);
    }

    public function test_user_puede_jugar(): void
    {

        $this->withoutExceptionHandling();

        $id = User::first()->id;

        $game = RollDice::factory()->create();

        $response = $this->post('/api/players/'. $id . '/games');

        $response->assertJsonFragment([
            'message' => 'Unauthorized'
        ]);

       
    }
   
}
