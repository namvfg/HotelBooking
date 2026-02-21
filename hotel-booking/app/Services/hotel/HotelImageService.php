<?php

namespace App\Services\hotel;

use App\Models\Hotel;
use App\Services\cloudinary\CloudinaryService;

class HotelImageService
{
    public function __construct(
        protected CloudinaryService $cloudinaryService
    ) {}

    public function uploadMany(Hotel $hotel, array $images): void
    {
        foreach ($images as $file) {
            $uploaded = $this->cloudinaryService->upload(
                $file,
                "hotels/{$hotel->id}"
            );

            $hotel->images()->create([
                "public_id" => $uploaded["public_id"],
                "url" => $uploaded["url"],
            ]);
        }
    }

    public function deleteMany(Hotel $hotel, array $imageIds): void
    {
        $images = $hotel->images()
            ->whereIn("id", $imageIds)
            ->get();

        foreach ($images as $image) {
            $this->cloudinaryService->delete($image->public_id);
            $image->delete();
        }
    }
}
