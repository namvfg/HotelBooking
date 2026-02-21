<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Http\Resources\Room\RoomDetailResource;
use App\Http\Resources\Room\RoomResource;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\hotel\HotelService;
use App\Services\room\RoomService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query("search");

        $request->validate([
            "hotel_id" => ["sometimes", "exists:hotels,id"],
            "room_type_id" => [
                "sometimes",
                Rule::exists("room_types", "id")
                    ->where(
                        fn($q) =>
                        $q->where("hotel_id", $request->hotel_id)
                    )
            ],
        ]);

        return RoomResource::collection(
            Room::with(["hotel", "roomType"])
                ->when($request->hotel_id, function ($q) use ($request) {
                    $q->where("hotel_id", $request->hotel_id);
                })
                ->when($request->room_type_id, function ($q) use ($request) {
                    $q->where("room_type_id", $request->room_type_id);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where("room_code", "like", "%{$search}%")
                            ->orWhereHas("hotel", function ($hq) use ($search) {
                                $hq->where("name", "like", "%{$search}%");
                            })
                            ->orWhereHas("roomType", function ($rq) use ($search) {
                                $rq->where("name", "like", "%{$search}%");
                            });
                    });
                })
                ->latest()
                ->paginate(config("pagination.per_page"))
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreRoomRequest $request,
        RoomService $roomService
    ) {
        $room = $roomService->createWithImages(
            $request->validated(),
            $request->file("images")
        );

        return (new RoomResource($room))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return (new RoomDetailResource($room))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateRoomRequest $request,
        Room $room,
        RoomService $roomService
    ) {
        $room = $roomService->updateWithImages(
            $room,
            $request->validated(),
            $request->file("images"),
            $request->input("delete_image_ids")
        );
        return (new RoomResource($room))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return response()->noContent();
    }
}
