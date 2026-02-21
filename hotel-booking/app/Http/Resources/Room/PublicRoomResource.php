<?php

namespace App\Http\Resources\Room;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicRoomResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'room_code' => $this->room_code,

            'primary_image' => $this->primaryImage
                ? [
                    'id' => $this->primaryImage->id,
                    'url' => $this->primaryImage->url,
                ]
                : null,
        ];
    }
}
