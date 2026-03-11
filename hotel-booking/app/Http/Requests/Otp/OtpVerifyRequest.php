<?php

namespace App\Http\Requests\Otp;

use Illuminate\Foundation\Http\FormRequest;

class OtpVerifyRequest extends FormRequest {
    public function rules() {
        return [
            "email" => "required|exists:otps,email",
            "code" => "required|string",
        ];
    }
}