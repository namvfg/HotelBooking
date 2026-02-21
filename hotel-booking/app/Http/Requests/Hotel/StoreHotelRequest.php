<?php

namespace App\Http\Requests\Hotel;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreHotelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => "required|string|max:100",
            "manager_id" => [
                "required",
                Rule::exists("users", "id")->where("role", "manager"),
            ],
            "address" => "required|string|max:255",
            "city" => "required|string|max:200",
            "country" => "required|string|max:200",
            "description" => "nullable|string",

            "images" => "nullable|array|max:" . config("custom.max_images_quantity"),
            "images.*" => config("image.image_validate_config"),
        ];
    }

    public function authorize(): bool
    {
        $role = Auth::user()->role;
        return $role === Role::ADMIN || $role === Role::MANAGER;
    }
}
