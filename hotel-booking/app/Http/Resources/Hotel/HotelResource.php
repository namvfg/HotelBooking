<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource {
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "manager_name" => $this->manager?->name,
            "city" => $this->city,
            "country" => $this->country,
            "is_active" => $this->is_active,
            "created_at" => $this->created_at->format(config("custom.date_format")),
        ];
    }
}