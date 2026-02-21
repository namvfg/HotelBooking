<?php

namespace App\Models;

use App\Services\cloudinary\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "room_id",
        "url",
        "public_id",
    ];

    protected static function booted()
    {
        static::deleting(function ($image) {
            app(CloudinaryService::class)->delete(($image->public_id));
        });
    }

    public function room() : BelongsTo {
        return $this->belongsTo(Room::class);
    }
}
