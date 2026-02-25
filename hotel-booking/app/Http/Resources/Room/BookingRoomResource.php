<?php

namespace App\Http\Resources\Room;

use App\Http\Resources\RoomImage\RoomImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingRoomResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "room_code" => $this->room_code,
            "base_price" => $this->roomType->base_price,
            "status" => $this->status,
            "images" => RoomImageResource::collection($this->images),
            "booked_dates" => $this->whenLoaded('bookings', function () {
                return $this->bookings->map(fn($b) => [
                    "from" => $b->checkin_date,
                    "to" => $b->checkout_date,
                ]);
            }),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
