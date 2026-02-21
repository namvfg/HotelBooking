<?php

namespace App\Http\Resources\Hotel;

use App\Http\Resources\HotelImage\HotelImageResource;
use App\Http\Resources\RoomType\PublicRoomTypeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicHotelDetailResouce extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'description' => $this->description,

            'primary_image' => $this->primaryImage,

            'images' => $this->images->map(fn ($img) => [
                "id" => $img->id,
                "url" => $img->url,
            ]),

            'amenities' => $this->amenities->map(fn ($a) => [
                "id" => $a->id,
                "name" => $a->name,
                "slug" => $a->slug,
            ]),

            'room_types' => PublicRoomTypeResource::collection($this->roomTypes),
        ];
    }
}
