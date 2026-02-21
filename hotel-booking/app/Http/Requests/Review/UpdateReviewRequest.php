<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest {
    public function rules() : array {
        return [
            "user_id" => "sometimes|exists:users,id",
            "hotel_id" => "sometimes|exists:hotels,id",
            "rating" => "sometimes|integer|min:1|max:5",
            "comment" => "sometimes|nullable|string"
        ];
    }

    public function authorize() : bool {
        return true;
    }
}