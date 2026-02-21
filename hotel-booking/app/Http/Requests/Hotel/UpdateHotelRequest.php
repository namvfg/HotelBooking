<?php

namespace App\Http\Requests\Hotel;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateHotelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "manager_id" => [
                "required",
                Rule::exists("users", "id")->where("role", "manager"),
            ],
            "name" => "sometimes|string|max:100",
            "address" => "sometimes|string|max:255",
            "city" => "sometimes|string|max:200",
            "country" => "sometimes|string|max:200",
            "description" => "sometimes|nullable|string",

            "images" => "sometimes|array|max:" . config("custom.max_images_quantity"),
            "images.*" => config("image.image_validate_config"),

            "delete_image_ids" => "sometimes|array",
            "delete_image_ids.*" => "exists:hotel_images,id",
        ];
    }

    public function authorize(): bool
    {
        $role = Auth::user()->role;
        return $role === Role::ADMIN || $role === Role::MANAGER;
    }
}
