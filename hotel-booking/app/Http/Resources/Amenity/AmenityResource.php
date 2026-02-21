<?php

namespace App\Http\Resources\Amenity;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AmenityResource extends JsonResource {
    public function toArray(Request $request) : array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "slug" => $this->slug,
            "created_at" => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}