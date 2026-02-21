<?php

namespace Database\Factories;

use App\Enums\RoomStatus;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'hotel_id' => Hotel::factory(),        
            'room_type_id' => RoomType::factory(),
            'room_code' => 'RM-' . $this->faker->unique()->numberBetween(100, 999),
            'status' => RoomStatus::AVAILABLE,
        ];
    }
}
