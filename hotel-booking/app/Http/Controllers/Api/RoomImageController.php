<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RoomImage\StoreRoomImageRequest;
use App\Http\Resources\HotelImage\HotelImageDetailResource;
use App\Http\Resources\RoomImage\RoomImageDetailResource;
use App\Http\Resources\RoomImage\RoomImageResource;
use App\Models\HotelImage;
use App\Models\RoomImage;
use App\Services\cloudinary\CloudinaryService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoomImageController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate(
            [
                "room_id" => "required|exists:rooms,id"
            ]
        );

        return RoomImageResource::collection(
            RoomImage::where("room_id", $request->room_id)
                ->latest()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomImageRequest $request, CloudinaryService $cloudinaryService)
    {
        $request->validate([
            "images" => "required|array|max:" . config("custom.max_images_quantity"),
            "images.*" => config("image.image_validate_config"),
        ]);

        foreach ($request->file("images") as $index => $file) {
            $uploaded = $cloudinaryService->upload($file, "rooms/".$request->room_id);

            RoomImage::create([
                "url" => $uploaded["url"],
                "public_id" => $uploaded["public_id"],
                "room_id" => $request->room_id
            ]);
        }

        return response()->json(["message" => "Upload Room Images successfully"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomImage $roomImage)
    {
        return (new RoomImageDetailResource($roomImage))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomImage $roomImage, CloudinaryService $cloudinaryService)
    {
        $cloudinaryService->delete($roomImage->public_id);
        $roomImage->delete();
        return response()->noContent();
    }
}
