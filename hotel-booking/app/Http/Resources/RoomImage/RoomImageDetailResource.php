<?php

namespace App\Http\Resources\RoomImage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomImageDetailResource extends JsonResource {
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "url" => $this->url,
            "public_id" => $this->public_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}