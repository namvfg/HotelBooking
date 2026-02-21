<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "email_verified_at" => $this->email_verified_at?->format(config("custom.date_format")),
            "phone" => $this->phone,
            "avatar_url" => $this->avatar_url,
            "avatar_public_id" => $this->avatar_public_id,
            "role" => $this->role->value,
            "created_at" => $this->created_at->format(config("custom.date_format")),
            "updated_at" => $this->updated_at->format(config("custom.date_format")),
        ];
    }
}
