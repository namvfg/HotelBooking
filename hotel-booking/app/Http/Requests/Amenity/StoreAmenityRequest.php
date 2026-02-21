<?php

namespace App\Http\Requests\Amenity;

use Illuminate\Foundation\Http\FormRequest;

class StoreAmenityRequest extends FormRequest {
    public function rules() : array
    {
        return [
            "name" => "required|string|max:50",
            "slug" => "required|string|max:50|unique:amenities,slug"
        ];
    }
}