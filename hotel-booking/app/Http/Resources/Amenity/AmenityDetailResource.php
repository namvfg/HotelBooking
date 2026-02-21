<?php

namespace App\Http\Resources\Amenity;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AmenityDetailResource extends JsonResource {
    public function toArray(Request $request) : array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "slug" => $this->slug,
            "created_at" => $this->created_at->format(config("custom.date_format")),
            "updated_at" => $this->updated_at->format(config("custom.date_format")),
        ];
    }
}