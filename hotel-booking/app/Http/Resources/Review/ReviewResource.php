<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource {
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "user_name" => $this->user?->name,
            "hotel_name" => $this->hotel?->name,
            "rating" => $this->rating,
            "comment" => $this->comment,
            "created_at" => $this->created_at,
        ];  
    }
}