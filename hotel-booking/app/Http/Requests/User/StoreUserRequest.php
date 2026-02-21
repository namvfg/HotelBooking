<?php

namespace App\Http\Requests\User;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => "required|string|max:150",
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
            "role" => [
                "required",
                Rule::enum(Role::class)
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
