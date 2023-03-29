<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RollDice>
 */
class RollDiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first(),
            'dice_1' => mt_rand(1,6),
            'dice_2' => mt_rand(1,6),
            'result' => mt_rand(0,1),
            
        ];
    }
}
