<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\StoreHotelRequest;
use App\Http\Requests\Hotel\UpdateHotelRequest;
use App\Http\Resources\Hotel\HotelDetailResource;
use App\Http\Resources\Hotel\HotelHotResource;
use App\Http\Resources\Hotel\HotelResource;
use App\Http\Resources\Hotel\PublicHotelDetailResouce;
use App\Models\Hotel;
use App\Services\hotel\HotelService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query("search");

        return HotelResource::collection(
            Hotel::with("manager")
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where("hotels.name", "like", "%{$search}%")
                            ->orWhere("hotels.city", "like", "%{$search}%")
                            ->orWhere("hotels.country", "like", "%{$search}%")
                            ->orWhereHas("manager", function ($mq) use ($search) {
                                $mq->where("name", "like", "%{$search}%");
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
        StoreHotelRequest $request,
        HotelService $hotelService
    ) {
        $hotel = $hotelService->createWithImages(
            $request->validated(),
            $request->file('images')
        );

        return (new HotelResource($hotel))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        return (new HotelDetailResource($hotel))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateHotelRequest $request,
        Hotel $hotel,
        HotelService $hotelService
    ) {
        $hotel = $hotelService->updateWithImages(
            $hotel,
            $request->validated(),
            $request->file("images"),
            $request->input("delete_image_ids")
        );

        return (new HotelResource($hotel))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return response()->noContent();
    }

    public function hotHotels()
    {
        $fromDate = Carbon::now()->subDays(30);

        $hotHotels = Hotel::with(['primaryImage', "manager"])
            ->withCount([
                'bookings as bookings_count' => function ($q) use ($fromDate) {
                    $q->where('bookings.status', 'CONFIRMED')
                        ->where('bookings.created_at', '>=', $fromDate);
                }
            ])
            ->orderByDesc('bookings_count')
            ->paginate(6);

        return HotelHotResource::collection($hotHotels);
    }

    public function detail(Hotel $hotel)
    {
        $hotel->load([
            'primaryImage',
            'images',
            'amenities:id,name,slug',

            'roomTypes:id,hotel_id,name,description,base_price,capacity',
            'roomTypes.rooms' => fn($q) =>
            $q->where('status', 'AVAILABLE')
                ->select('id', 'room_type_id', 'room_code'),

            'roomTypes.rooms',
        ]);

        return new PublicHotelDetailResouce($hotel);
    }

    public function detailSearch(Request $request)
    {
        $hotels = Hotel::query()
            ->with('primaryImage')

            // keyword
            ->when(
                $request->keyword,
                fn($q) =>
                $q->where('name', 'like', "%{$request->keyword}%")
            )

            // city
            ->when(
                $request->city,
                fn($q) =>
                $q->where('city', 'like', "%{$request->city}%")
            )

            // country
            ->when(
                $request->country,
                fn($q) =>
                $q->where('country', 'like', "%{$request->country}%")
            )

            // price filter → ROOM TYPES
            ->when(
                $request->price_min || $request->price_max,
                function ($q) use ($request) {
                    $q->whereHas('roomTypes', function ($rt) use ($request) {
                        if ($request->price_min) {
                            $rt->where('base_price', '>=', $request->price_min);
                        }

                        if ($request->price_max) {
                            $rt->where('base_price', '<=', $request->price_max);
                        }
                    });
                }
            )

            // availability → ROOMS + BOOKINGS
            ->when(
                $request->checkin && $request->checkout,
                function ($q) use ($request) {
                    $q->whereHas('roomTypes.rooms', function ($room) use ($request) {
                        $room->whereDoesntHave('bookings', function ($booking) use ($request) {
                            $booking
                                ->where('checkin_date', '<', $request->checkout)
                                ->where('checkout_date', '>', $request->checkin);
                        });
                    });
                }
            )

            ->paginate(9);

        return HotelHotResource::collection($hotels);
    }
}
