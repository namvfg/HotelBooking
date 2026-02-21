<?php

namespace App\Enums;

enum PaymentType: string {
    case DEPOSIT = "deposit";
    case PAYMENT = "payment";
    case REFUND = "refund";
}