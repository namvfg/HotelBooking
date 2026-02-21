<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        "hotel_id",
        "name",
        "description",
        "capacity",
        "base_price",
    ];

    public function hotel() : BelongsTo {
        return $this->belongsTo(Hotel::class);
    }

    public function rooms() : HasMany {
        return $this->hasMany(Room::class);
    }
}
