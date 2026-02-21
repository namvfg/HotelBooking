<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->manager(),
            'name' => fake()->company() . ' Hotel',
            'address' => fake()->address(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'description' => fake()->paragraph(),
        ];
    }
}
