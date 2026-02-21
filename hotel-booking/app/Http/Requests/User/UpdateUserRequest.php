<?php

namespace App\Http\Requests\User;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        $user = $this->route("user");

        return [
            "name" => "sometimes|string|max:150",
            "email" => [
                "sometimes",
                "email",
                "max:150",
                Rule::unique("users", "email")->ignore($user),
            ],
            "phone" => "sometimes|string|max:20|regex:/^[0-9+\-\s]+$/",
            "role" => [
                "sometimes",
                Rule::enum(Role::class)
            ],
            "password" => [
                "sometimes",
                "confirmed",
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],

        ];
    }
}
