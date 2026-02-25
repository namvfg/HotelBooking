<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResouce extends JsonResource {
    public function toArray(Request $request)
    {
        return [
            "amount" => $this->amount,
            "method" => $this->method,
            "type" => $this->type,
            "status" => $this->status,
            "created_at" => $this->created_at
        ];
    }
}