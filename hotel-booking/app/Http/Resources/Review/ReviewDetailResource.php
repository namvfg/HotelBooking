<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewDetailResource extends JsonResource {
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "user" => $this->user,
            "hotel" => $this->hotel,
            "rating" => $this->rating,
            "comment" => $this->comment,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];  
    }
}