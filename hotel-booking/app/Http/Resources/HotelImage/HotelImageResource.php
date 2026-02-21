<?php

namespace App\Http\Resources\HotelImage;

use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Request;

class HotelImageResource extends JsonResource {
    public function toArray(Request $request) : array {
        return [
           "id" => $this->id,
           "url" => $this->url,
           "created_at" => $this->created_at,
        ];
    }
}