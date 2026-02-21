<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelImage\StoreHotelImageRequest;
use App\Http\Resources\Hotel\HotelDetailResource;
use App\Http\Resources\HotelImage\HotelImageResource;
use App\Models\HotelImage;
use App\Services\cloudinary\CloudinaryService;
use Illuminate\Http\Request;

class HotelImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            "hotel_id" => ["required", "exists:hotels,id"],
        ]);

        return HotelImageResource::collection(
            HotelImage::where("hote_id", $request->hotel_id)
                ->latest()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHotelImageRequest $request, CloudinaryService $cloudinaryService)
    {
        $request->validate([
            "images" => "required|array|max:" . config("custom.max_images_quantity"),
            "images.*" => config("image.image_validate_config"),
        ]);

        foreach ($request->file("images") as $index => $file) {
            $uploaded = $cloudinaryService->upload($file, "hotels/" . $request->hotel_id);
            HotelImage::create([
                "hotel_id" => $request->hotel_id,
                "public_id" => $uploaded["url"],
                "url" => $uploaded["public_id"],
            ]);
        }

        return response()->json([
            "message" => "Upload Hotel Images successfully",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelImage $hotelImage)
    {
        return (new HotelDetailResource($hotelImage))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelImage $hotelImage, CloudinaryService $cloudinaryService)
    {
        $cloudinaryService->delete($hotelImage->public_id);
        $hotelImage->delete();
        return response()->noContent();
    }
}
