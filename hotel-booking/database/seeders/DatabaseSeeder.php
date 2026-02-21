<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelImage;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        User::factory()->admin()->create();
        User::factory()->count(3)->manager()->create();
        User::factory()->count(10)->create();

        // Amenities
        $amenities = Amenity::factory()->count(10)->create();

        $hotels = Hotel::factory()
            ->count(5)
            ->create()
            ->each(function ($hotel) use ($amenities) {

                $hotel->amenities()->attach(
                    $amenities->random(rand(3, 6))->pluck('id')
                );

                HotelImage::factory()->count(3)->create([
                    'hotel_id' => $hotel->id
                ]);

                RoomType::factory()
                    ->count(3)
                    ->sequence(
                        ['name' => 'Standard'],
                        ['name' => 'Deluxe'],
                        ['name' => 'Suite'],
                    )
                    ->create(['hotel_id' => $hotel->id])
                    ->each(function ($roomType) {

                        $prefix = strtoupper(substr($roomType->name, 0, 2));

                        foreach (range(1, 5) as $index) {

                            $room = Room::factory()->create([
                                'hotel_id' => $roomType->hotel_id,
                                'room_type_id' => $roomType->id,
                                'room_code' => $prefix . '-' . $index,
                            ]);

                            RoomImage::factory()
                                ->count(3)
                                ->create([
                                    'room_id' => $room->id,
                                ]);
                        }
                    });
            });

        // Bookings + Payments
        Booking::factory()
            ->count(20)
            ->create()
            ->each(function ($booking) {
                Payment::factory()->create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_price,
                ]);
            });

        // Reviews
        Review::factory()->count(30)->create();
    }
}
