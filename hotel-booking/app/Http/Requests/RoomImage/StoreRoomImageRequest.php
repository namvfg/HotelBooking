<?php

namespace App\Http\Requests\RoomImage;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomImageRequest extends FormRequest {
    public function rules() : array {
        return [
            "room_id" => "required|exists:rooms,id"
        ];
    }

    public function authorize() : bool {
        return true;
    }
}