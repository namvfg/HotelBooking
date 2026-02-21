<?php

namespace App\Http\Requests\Room;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreRoomRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "hotel_id" => "required|exists:hotels,id",
            "room_type_id" => [
                "required",
                Rule::exists("room_types", "id")
                    ->where(
                        fn($q) =>
                        $q->where("hotel_id", $this->hotel_id)
                    )
            ],
            "room_code" => "required|string|max:10",
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
