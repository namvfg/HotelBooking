<?php

namespace App\Models;

use App\Enums\RoomStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        "hotel_id",
        "room_type_id",
        "room_code",
        "status"
    ];

    protected function casts(): array
    {
        return [
            "status" => RoomStatus::class
        ];
    }

    protected static function booted()
    {
        static::deleting(function ($room) {
            $room->images()->delete();
        });
    }

    public function isAvailable(): bool
    {
        return $this->status === RoomStatus::AVAILABLE;
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(RoomImage::class)->oldestOfMany();
    }
}
