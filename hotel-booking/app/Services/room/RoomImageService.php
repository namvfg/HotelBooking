<?php

namespace App\Services\room;

use App\Models\Room;
use App\Services\cloudinary\CloudinaryService;

class RoomImageService
{
    public function __construct(
        protected CloudinaryService $cloudinaryService
    ) {}

    public function uploadMany(Room $room, array $images): void
    {
        foreach ($images as $file) {
            $uploaded = $this->cloudinaryService->upload(
                $file,
                "rooms/{$room->id}"
            );

            $room->images()->create([
                "public_id" => $uploaded["public_id"],
                "url" => $uploaded["url"],
            ]);
        }
    }

    public function deleteMany(Room $room, array $imageIds) : void {
        $images = $room->images()
            ->whereIn("id", $imageIds)
            ->get();

        foreach($images as $file) {
            $this->cloudinaryService->delete($file->public_id);
            $file->delete();
        }
    }
}
