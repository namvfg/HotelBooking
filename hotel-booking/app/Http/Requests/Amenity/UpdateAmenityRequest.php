<?php

namespace App\Http\Requests\Amenity;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAmenityRequest extends FormRequest
{
    public function rules()
    {
        return [
            "name" => "required|string|max:50",
            "slug" => "required|string|max:50|unique:amenities,slug," . $this->amenity->id,
        ];
    }
}
