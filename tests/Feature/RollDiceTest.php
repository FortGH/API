<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\RollDice;


class RollDiceTest extends TestCase
{
    //use WithoutMiddleware;

    public function test_user_puede_ver_sus_partidas(): void
    {
        $this->withoutExceptionHandling();

        $user = User::first();
 
        $response = $this->actingAs($user,'api')
                         ->get('/api/players/52/games');
        
                         $this->assertEquals(200, $response->getStatusCode());

        // $games = RollDice::factory()->count(25)->create();

        // $user = User::first();

        // $id = $user->id;

        // $token = $user->createToken('token')->accessToken;

        // $response = $this->withHeaders([
        //     'Authorization' => 'Bearer' . $token,
        //     'X-Requested-With' => 'XMLHttpRequest'
        //     ])->get('/api/players/'. $id . '/games');

        // $response->assertStatus(200);
        // //json('GET','/api/players/'. $id . '/games');
    }
}
