<?php

namespace App\Http\Resources\HotelImage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelImageDetailResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "hotel" => $this->hotel,
            "url" => $this->url,
            "public_id" => $this->public_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
