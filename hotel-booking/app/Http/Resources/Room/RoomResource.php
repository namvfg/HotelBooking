<?php

namespace App\Http\Resources\Room;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource {
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "hotel_name" => $this->hotel?->name,
            "room_type_name" => $this->roomType?->name,
            "room_code" => $this->room_code,
            "created_at" => $this->created_at,
        ];
    }
}