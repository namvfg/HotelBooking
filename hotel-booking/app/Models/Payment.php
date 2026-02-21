<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        "booking_id",
        "amount",
        "method",
        "type",
        "status",
        "paid_at",
        "transaction_code"
    ];

    protected function casts() : array
    {
        return [
            "method" => PaymentMethod::class,
            "type" => PaymentType::class,
            "status" => PaymentStatus::class
        ];
    }

    public function booking() : BelongsTo {
        return $this->belongsTo(Booking::class);
    }
}
