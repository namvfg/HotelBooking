<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentDetailResouce extends JsonResource {
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "booking" => $this->booking,
            "amount" => $this->amount,
            "method" => $this->method,
            "type" => $this->type,
            "status" => $this->status,
            "paid_at" => $this->paid_at,
            "transaction_code" => $this->transaction_code,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}