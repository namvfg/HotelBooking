<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "role" => $this->role->value,
            "avatar_url" => $this->avatar_url,
            "created_at" => $this->created_at->format(config("custom.date_format")),
        ];
    }
}