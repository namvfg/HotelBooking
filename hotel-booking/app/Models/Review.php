<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        "user_id",
        "hotel_id",
        "rating",
        "comment"
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function hotel() : BelongsTo {
        return $this->belongsTo(Hotel::class);
    }
}
