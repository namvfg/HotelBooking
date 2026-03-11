<?php

namespace App\Enums;

enum OtpType: string {
    case RESET_PASSWORD = "reset_password";
    case REGISTER = "register";
}