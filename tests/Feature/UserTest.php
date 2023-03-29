<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\RollDice;


class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_mostrar_todos_los_usuarios(): void
    {
        $this->withoutExceptionHandling();

        $user = User::get()->first();
        $user->admin = 1;
        $user->save();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('token')->accessToken,
        ])->get('/api/players');

        $response->assertJsonStructure([
            'message',
            'users' => [
                '*' => [
                    "id",
                    "name",
                    "email",
                    "email_verified_at",
                    "admin",
                    "created_at",
                    "updated_at",
                    "succes_rate"
                ]
                ],
            'status'
        ]);
    }

    public function test_cambiar_nombre_usuario(): void
    {
        $this->withoutExceptionHandling();

        $user = User::inRandomOrder()->first();
        
        $name = User::factory()->make();
       
        $token = $user->createToken('token')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/api/players/'. $user->id,['name' => $name->name]);

        $response->assertJsonStructure([
            'message',
            'user',
            'status'
        ]);
    }

    public function test_mostrar_ranking_de_jugadores(): void
    {
        $this->withoutExceptionHandling();

        $user = User::get()->first();
       
        $token = $user->createToken('token')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/players/ranking');

        $response->assertJsonStructure([
            'message',
            'users' => [
                '*' => [
                    "id",
                    "name",
                    "succes_rate"
                ]
                ],
            "average success rate",
            'status'
        ]);
    }

    public function test_mostrar_jugador_mejor_posicionado_en_el_ranking(): void
    {
        $this->withoutExceptionHandling();

        $user = User::get()->first();
       
        $token = $user->createToken('token')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/players/ranking/winner');

        $response->assertJsonStructure([
            'message',
            'user' => 
                [
                    "id",
                    "name",
                    "succes_rate"
                ],
            'status'
        ]);
    }

    public function test_mostrar_jugador_peor_posicionado_en_el_ranking(): void
    {
        $this->withoutExceptionHandling();

        $user = User::get()->first();
       
        $token = $user->createToken('token')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/players/ranking/loser');

        $response->assertStatus(200);
    }

    public function test_mostrar_average_success_rate(): void
    {
        $this->withoutExceptionHandling();

        $games = RollDice::get()->count();
       
        $winner = RollDice::where('result',1)->get()->count();

        $response = round(($winner/$games*100),2);

        //$response->assertSee(',');
    }
}
