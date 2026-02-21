<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\AmenityController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\HotelImageController;
use App\Http\Controllers\Api\ManagerController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\RoomImageController;
use App\Http\Controllers\Api\RoomTypeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\User\UserAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Authenticated GET APIs (NO ROLE)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // ===== AMENITIES =====
    Route::get('amenities', [AmenityController::class, 'index']);
    Route::get('amenities/{amenity}', [AmenityController::class, 'show']);

    // ===== ROOM TYPES =====
    Route::get('room-types', [RoomTypeController::class, 'index']);
    Route::get('room-types/{roomType}', [RoomTypeController::class, 'show']);

    // ===== REVIEWS =====
    Route::get('reviews', [ReviewController::class, 'index']);
    Route::get('reviews/{review}', [ReviewController::class, 'show']);

    // ===== MANAGERS =====
    Route::get('managers', [ManagerController::class, 'index']);
    Route::get('managers/{manager}', [ManagerController::class, 'show']);


    // ===== REVIEWS =====
    Route::post('reviews', [ReviewController::class, 'store']);
    Route::put('reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('reviews/{review}', [ReviewController::class, 'delete']);

    // ===== BOOKINGS =====
    Route::apiResource('bookings', BookingController::class);
});

/*
|--------------------------------------------------------------------------
| Admin-only APIs (WRITE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // ===== USERS =====
    Route::apiResource('users', UserController::class);

    // ===== AMENITIES =====
    Route::post('amenities', [AmenityController::class, 'store']);
    Route::put('amenities/{amenity}', [AmenityController::class, 'update']);
    Route::delete('amenities/{amenity}', [AmenityController::class, 'destroy']);

    // ===== HOTELS =====
    Route::post('hotels', [HotelController::class, 'store']);
    Route::put('hotels/{hotel}', [HotelController::class, 'update']);
    Route::delete('hotels/{hotel}', [HotelController::class, 'destroy']);

    // ===== HOTEL IMAGES =====
    Route::apiResource('hotel-images', HotelImageController::class)->except(['index', 'show']);

    // ===== ROOMS =====
    Route::post('rooms', [RoomController::class, 'store']);
    Route::put('rooms/{room}', [RoomController::class, 'update']);
    Route::delete('rooms/{room}', [RoomController::class, 'destroy']);

    // ===== ROOM IMAGES =====
    Route::apiResource('room-images', RoomImageController::class)->except(['index', 'show']);

    // ===== ROOM TYPES =====
    Route::apiResource('room-types', RoomTypeController::class)->except(['index', 'show']);

    // ===== PAYMENTS =====
    Route::apiResource('payments', PaymentController::class);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {});

Route::prefix("admin")->group(function () {
    Route::get("/me", [AuthController::class, "me"])
        ->middleware("auth:sanctum");
});

// ===== HOTELS =====
Route::get('hotels', [HotelController::class, 'index']);
Route::get('hotels/{hotel}', [HotelController::class, 'show']);
Route::get('hot-hotels', [HotelController::class, 'hotHotels']);
Route::get('hotels/{hotel}/detail', [HotelController::class, 'detail']);
Route::get('search-hotels', [HotelController::class, 'detailSearch']);

// ===== ROOMS =====
Route::get('rooms', [RoomController::class, 'index']);
Route::get('rooms/{room}', [RoomController::class, 'show']);

Route::post("/admin/login", [AuthController::class, "login"]);

Route::post('/register', RegisterController::class);
Route::post("/login", [UserAuthController::class, "login"]);
Route::prefix("user")->middleware("auth:sanctum")->group(function () {
    Route::get("/me", [UserAuthController::class, "me"]);
});
