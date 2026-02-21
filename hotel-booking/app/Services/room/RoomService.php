<?php

namespace App\Services\room;

use App\Models\Room;
use Illuminate\Support\Facades\DB;

class RoomService
{
    public function __construct(
        protected RoomImageService $roomImageService
    ) {}

    public function createWithImages(array $data, ?array $images): Room
    {
        return DB::transaction(function () use ($data, $images) {
            $room = Room::create(...$data);

            if ($images) {
                $this->roomImageService->uploadMany($room, $images);
            }

            return $room;
        });
    }

    public function updateWithImages(
        Room $room,
        array $data,
        $newImages,
        $deleteImagesIds
    ) {
        $room->update($data);

        if ($deleteImagesIds) {
            $this->roomImageService->deleteMany($room, $deleteImagesIds);
        }

        if ($newImages) {
            $this->roomImageService
                ->uploadMany($room, $newImages);
        }
        return $room->refresh();
    }
}
