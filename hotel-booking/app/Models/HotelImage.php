<?php

namespace App\Models;

use App\Services\cloudinary\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelImage extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        "hotel_id",
        "url",
        "public_id"
    ];

    protected static function booted()
    {
        static::deleting(function ($image) {
            app(CloudinaryService::class)->delete($image->public_id);
        });
    }

    public function hotel() : BelongsTo {
        return $this->belongsTo(Hotel::class);
    }
}
