<?php

namespace App\Http\Resources\RoomImage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomImageResource extends JsonResource {
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "url" => $this->url,
            "created_at" => $this->created_at
        ];
    }
}