<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'amount' => fake()->numberBetween(500000, 5000000),
            'method' => PaymentMethod::CASH,
            'type' => PaymentType::PAYMENT,
            'status' => PaymentStatus::SUCCESS,
            'paid_at' => now(),
            'transaction_code' => strtoupper(fake()->bothify('TXN###')),
        ];
    }
}
