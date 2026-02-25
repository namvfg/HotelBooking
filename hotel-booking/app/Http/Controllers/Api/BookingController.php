<?php

namespace App\Http\Controllers\Api;

use App\Enums\BookingStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\Booking\BookingDetailResource;
use App\Http\Resources\Booking\BookingResource;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            "room_id" => ["required", "exists:rooms,id"]
        ]);

        return BookingResource::collection(
            Booking::where("room_id", $request->room_id)
                ->where("user_id", Auth::id())
                ->latest()
                ->paginate(config("pagination.per_page")),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $room = Room::findOrFail($request->room_id);

            $conflict = Booking::where('room_id', $room->id)
                ->whereIn('status', [
                    BookingStatus::PENDING,
                    BookingStatus::CONFIRMED
                ])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('checkin_date', [$request->checkin_date, $request->checkout_date])
                        ->orWhereBetween('checkout_date', [$request->checkin_date, $request->checkout_date])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('checkin_date', '<=', $request->checkin_date)
                                ->where('checkout_date', '>=', $request->checkout_date);
                        });
                })
                ->exists();

            if ($conflict) {
                return response()->json([
                    'message' => 'Phòng đã được đặt trong khoảng thời gian này.'
                ], 422);
            }

            $checkin = Carbon::parse($request->checkin_date);
            $checkout = Carbon::parse($request->checkout_date);

            $days = $checkin->diffInDays($checkout) + 1; // +1 vì cùng ngày vẫn tính 1 đêm

            $totalPrice = $days * $room->roomType->base_price;


            $booking = Booking::create([
                'user_id'       => auth('sanctum')->id(),
                'room_id'       => $room->id,
                'checkin_date'  => $request->checkin_date,
                'checkout_date' => $request->checkout_date,
                'total_price'   => $totalPrice,
                'status'        => BookingStatus::PENDING,
                'note'          => $request->note,
            ]);


            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount'     => $totalPrice,
                'method'     => PaymentMethod::VNPAY,
                'type'       => PaymentType::PAYMENT,
                'status'     => PaymentStatus::PENDING,
            ]);

            return response()->json([
                'message' => 'Tạo booking và payment thành công',
                'data' => [
                    'booking' => $booking->load('room'),
                    'payment' => $payment
                ]
            ], 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        return (new BookingDetailResource($booking))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        if ($booking->status !== BookingStatus::PENDING) {
            return response()->json([
                "message" => "Booking cannot be updated in current status",
            ], 422);
        }

        $booking->update($request->validated());
        return (new BookingResource($booking))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->noContent();
    }

    public function myBookings(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::with([
            'room',
            'payment'
        ])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return BookingResource::collection($bookings);
    }
}
