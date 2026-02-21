<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkin = fake()->dateTimeBetween('-5 days', '+5 days');
        $checkout = (clone $checkin)->modify('+' . rand(1, 5) . ' days');

        return [
            'user_id' => User::factory(),
            'room_id' => Room::factory(),
            'checkin_date' => $checkin,
            'checkout_date' => $checkout,
            'total_price' => fake()->numberBetween(500000, 5000000),
            'note' => fake()->optional()->sentence(),
            'status' => BookingStatus::CONFIRMED,
        ];
    }
}
