<?php

namespace App\Http\Resources\Hotel;

use App\Http\Resources\HotelImage\HotelImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelDetailResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "manager" => $this->manager,
            "city" => $this->city,
            "country" => $this->country,
            "address" => $this->address,
            "is_active" => $this->is_active,
            "images" => HotelImageResource::collection($this->images),
            "created_at" => $this->created_at->format(config("custom.date_format")),
            "updated_at" => $this->updated_at->format(config("custom.date_format")),
        ];
    }
}
