<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest {
    public function rules() : array {
        return [
            "user_id" => "required|exists:users,id",
            "hotel_id" => "required|exists:hotels,id",
            "rating" => "required|integer|min:1|max:5",
            "comment" => "nullable|string"
        ];
    }

    public function authorize() : bool {
        return true;
    }
}