<?php

namespace Database\Factories;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'role' => Role::USER,
            'avatar_url' => fake()->imageUrl(800, 600, 'avatar'),
            'avatar_public_id' => fake()->name(),
            'password' => Hash::make("123456"),
        ];
    }

    public function admin()
    {
        return $this->state(fn() => ['role' => Role::ADMIN]);
    }

    public function manager()
    {
        return $this->state(fn() => ['role' => Role::MANAGER]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
