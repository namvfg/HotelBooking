<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomType\StoreRoomTypeRequest;
use App\Http\Requests\RoomType\UpdateRoomTypeRequest;
use App\Http\Resources\RoomType\RoomTypeDetailResource;
use App\Http\Resources\RoomType\RoomTypeResource;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            "hotel_id" => ["nullable", "exists:hotels,id"]
        ]);

        $search = $request->search;


        return RoomTypeResource::collection(
            RoomType::when($request->hotel_id, function ($q) use ($request) {
                $q->where("hotel_id", $request->hotel_id);
            })
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($sq) use ($search) {
                        $sq->where("name", "like", "%{$search}%")
                            ->orWhereHas("hotel", function ($hq) use ($search) {
                                $hq->where("name", "like", "%{$search}%");
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
    public function store(StoreRoomTypeRequest $request)
    {
        $roomType = RoomType::create($request->validated());
        return (new RoomTypeResource($roomType))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomType $roomType)
    {
        return (new RoomTypeDetailResource($roomType))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomTypeRequest $request, RoomType $roomType)
    {
        $roomType->update($request->validated());
        return (new RoomTypeDetailResource($roomType))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomType $roomType)
    {
        $roomType->delete();
        return response()->noContent();
    }
}
