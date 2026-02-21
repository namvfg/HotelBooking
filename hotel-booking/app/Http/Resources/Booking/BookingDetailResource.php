<?php

namespace App\Http\Resources\Booking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingDetailResource extends JsonResource {
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "user" => $this->user,
            "room" => $this->room,
            "checkin_date" => $this->checkin_date,
            "checkout_date" => $this->checkout_date,
            "total_price" => $this->total_price,
            "note" => $this->note,
            "status" => $this->status,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}

