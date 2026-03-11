<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        "email",
        "code",
        "type",
        "expired_at",
    ];

    public function isVerified() : bool {
        return $this->is_verified === true;
    }
}
