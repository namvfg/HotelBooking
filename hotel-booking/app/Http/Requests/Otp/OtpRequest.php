<?php

namespace App\Http\Requests\Otp;

use App\Enums\OtpType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OtpRequest extends FormRequest {
    public function rules() : array {
        return [
            "email" => "required|email|unique:users,email",
            "type" => [
                "required",
                Rule::enum(OtpType::class)
            ],
        ];
    }

    public function authorize() : bool {
        return true;
    }
}