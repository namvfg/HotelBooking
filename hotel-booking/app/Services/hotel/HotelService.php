<?php

namespace App\Services\hotel;

use App\Enums\Role;
use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HotelService
{
    public function __construct(
        protected HotelImageService $hotelImageService
    ) {}

    public function createWithImages(array $data, ?array $images): Hotel
    {
        $managerId = Auth::user()->role === Role::ADMIN
            ? $data['manager_id']
            : Auth::id();

        return DB::transaction(function () use ($data, $images, $managerId) {
            $hotel = Hotel::create([
                ...$data,
                "user_id" => $managerId,
            ]);

            if ($images) {
                $this->hotelImageService->uploadMany($hotel, $images);
            }

            return $hotel;
        });
    }

    public function updateWithImages(
        Hotel $hotel,
        array $data,
        ?array $newImages = null,
        ?array $deleteImageIds = null
    ): Hotel {
        return DB::transaction(function () use (
            $hotel,
            $data,
            $newImages,
            $deleteImageIds
        ) {
            $managerId = Auth::user()->role === Role::ADMIN
                ? $data['manager_id']
                : Auth::id();

            $hotel->update([
                ...$data,
                "user_id" => $managerId
            ]);

            if ($deleteImageIds) {
                $this->hotelImageService
                    ->deleteMany($hotel, $deleteImageIds);
            }

            if ($newImages) {
                $this->hotelImageService
                    ->uploadMany($hotel, $newImages);
            }

            return $hotel->refresh();
        });
    }
}
