<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "user_id" => "nullable|exists:users,id",
            "room_id" => "required|exists:rooms,id",
            "checkin_date" => "required|date|before:checkout_date",
            "checkout_date" => "required|date|after:checkin_date",
            "note" => "nullable",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
