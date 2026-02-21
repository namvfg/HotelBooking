<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoomImage>
 */
class RoomImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'url' => "https://res.cloudinary.com/dcee16rsp/image/upload/v1770530019/000A4060-HDR_copy1_Copy_ejrymz.webp",
            'public_id' => fake()->name(),
        ];
    }
}
