<?php

namespace App\Http\Requests\HotelImage;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelImageRequest extends FormRequest {
    public function rules() : array {
        return [
            "hotel_id" => [
                "required",
                "exists:hotels,id"
            ],
        ];
    }

    public function authorize() : bool {
        return true;
    }
}