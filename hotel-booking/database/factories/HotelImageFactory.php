<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HotelImage>
 */
class HotelImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_id' => Hotel::factory(),
            'url' => "https://res.cloudinary.com/dcee16rsp/image/upload/v1770530019/DJI_0118_1_twfxpy.webp",
            'public_id' => fake()->name(),
        ];
    }
}
