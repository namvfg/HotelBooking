<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "note" => "nullable|sometimes"
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
