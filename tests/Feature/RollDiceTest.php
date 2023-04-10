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

        $user = User::latest()->first();
        $id = $user->id;
 
        $response = $this->actingAs($user,'api')
                         ->get('/api/players/' . $id . '/games');
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $response->assertStatus(200);

        $response->assertJsonIsObject();
    }

    public function test_user_puede_jugar(): void
    {

        $this->withoutExceptionHandling();

        $user =User::inRandomOrder()->first();

        $id = $user->id;

        $response = $this->actingAs($user,'api')
                         ->post('/api/players/' . $id . '/games');
        
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertJsonFragment([
            'message' => 'game created successfully'
        ]);
    }

    public function test_user_puede_eliminar_sus_juegos()
    {
        $this->withoutExceptionHandling();

        $user = User::first();

        $id = $user->id;

        $response = $this->actingAs($user,'api')
                         ->delete('/api/players/' . $id . '/games');
        
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertJsonFragment([
            'message' => 'games deleted successfully'
        ]);
    }
}
