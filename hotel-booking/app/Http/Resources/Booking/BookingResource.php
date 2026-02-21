<?php

namespace App\Http\Resources\Booking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource {
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "user_name" => $this->user?->name,
            "room_code" => $this->room?->room_code,
            "checkin_date" => $this->checkin_date,
            "checkout_date" => $this->checkout_date,
            "total_price" => $this->total_price,
            "status" => $this->status,
            "created_at" => $this->created_at,
        ];
    }
}

