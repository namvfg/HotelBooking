<?php

namespace App\Http\Requests\Room;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "hotel_id" => "sometimes|exists:hotels,id",
            "room_type_id" => [
                "sometimes",
                Rule::exists("room_types", "id")
                    ->where(
                        fn($q) =>
                        $q->where("hotel_id", $this->hotel_id)
                    )
            ],
            "room_code" => "sometimes|string|max:10",

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
