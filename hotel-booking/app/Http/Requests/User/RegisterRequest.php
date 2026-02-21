<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            "email" => "required|string|email|max:150|unique:users,email",
            "phone" => "required|string|max:20|regex:/^[0-9+\-\s]+$/",
            "password" => [
                "required",
                "confirmed",
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'is_manager' => ['nullable', 'string'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
