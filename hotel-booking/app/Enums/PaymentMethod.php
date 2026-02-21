<?php

namespace App\Enums;

enum PaymentMethod: string {
    case CASH = "cash";
    case VNPAY = "vnpay";
    case MOMO = "momo";
    case ZALOPAY = "zalopay";
}