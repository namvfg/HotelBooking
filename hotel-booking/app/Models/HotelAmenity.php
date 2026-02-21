<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelAmenity extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        "hotel_id",
        "amenity_id"
    ];

    public function hotel() : BelongsTo {
        return $this->belongsTo(Hotel::class);
    }

    public function amenity() : BelongsTo {
        return $this->belongsTo(Amenity::class);
    }
}
