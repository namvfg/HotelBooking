<?php

namespace App\Http\Resources\Room;

use App\Http\Resources\RoomImage\RoomImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomDetailResource extends JsonResource {
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "hotel" => $this->hotel,
            "room_type" => $this->room_type,
            "room_code" => $this->room_code,
            "base_price" => $this->roomType->base_price,
            "status" => $this->status,
            "images" => RoomImageResource::collection($this->images),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];  
    }
}