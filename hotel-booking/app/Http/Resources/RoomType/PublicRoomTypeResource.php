<?php

namespace App\Http\Resources\RoomType;

use App\Http\Resources\Room\PublicRoomResource;
use App\Http\Resources\Room\RoomResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicRoomTypeResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'base_price' => $this->base_price,
            'description' => $this->description,

            'rooms' => PublicRoomResource::collection(
                $this->whenLoaded('rooms')
            ),
        ];
    }
}
