<?php

namespace App\Http\Requests\RoomType;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreRoomTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "hotel_id" => "required|exists:hotels,id",
            "name" => [
                "required",
                "string",
                "max:50",
                Rule::unique("room_types", "name")->where(
                    fn($q) =>
                    $q->where("hotel_id", $this->hotel_id)
                )
            ],
            "description" => "nullable|string",
            "capacity" => "required|integer|min:1|max:255",
            "base_price" => "required|numeric|min:0"
        ];
    }

    public function authorize(): bool
    {
        $role = Auth::user()->role;
        return $role === Role::ADMIN || $role === Role::MANAGER;
    }
}
