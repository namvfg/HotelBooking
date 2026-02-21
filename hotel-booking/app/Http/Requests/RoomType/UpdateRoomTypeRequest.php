<?php

namespace App\Http\Requests\RoomType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "hotel_id" => "sometimes|exists:hotels,id",
            "name" => [
                "sometimes",
                "string",
                "max:50",
                Rule::unique("room_types")->where(
                    fn($q) =>
                    $q->where("hotel_id", $this->route("hotel")->id)
                        ->ignore($this->route("roomType")->id)
                )
            ],
            "description" => "sometimes|nullable|string",
            "capacity" => "sometimes|integer|min:1|max:255",
            "base_price" => "sometimes|numeric|min:0"
        ];
    }
    public function authorize(): bool
    {
        return true;
    }
}
