<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        "user_id",
        "name",
        "address",
        "city",
        "country",
        "description",
    ];

    protected static function booted()
    {
        static::deleting(function ($hotel) {
            $hotel->images()->delete();
        });
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(HotelImage::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(HotelImage::class)->oldestOfMany();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, "hotel_amenity");
    }

    public function bookings()
    {
        return $this->hasManyThrough(
            Booking::class,
            Room::class,
            'hotel_id', 
            'room_id', 
            'id',
            'id'
        );
    }
}
