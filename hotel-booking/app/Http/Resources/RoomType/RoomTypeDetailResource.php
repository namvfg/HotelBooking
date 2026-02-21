<?php

namespace App\Http\Resources\RoomType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeDetailResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,

            "hotel" => $this->hotel,
            
            "description" => $this->description,
            "capacity" => $this->capacity,
            "base_price" => $this->base_price,
            "is_active" => $this->is_active,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at 
        ];
    }
}
