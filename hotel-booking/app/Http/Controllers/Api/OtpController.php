<?php

use App\Http\Controllers\Controller;
use App\Http\Requests\Otp\OtpRequest;
use App\Http\Requests\Otp\OtpVerifyRequest;
use App\Mail\OtpMail;
use App\Models\Otp;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller {
    public function requestRegisterOtp(OtpRequest $request) {

        $request->validated();

        Otp::where("email", $request->email)
            ->where("type", request()->type)
            ->delete();

        $otp = rand(100000, 999999);

        Otp::created([
            "email" => $request->email,
            "code" => Hash::make($otp),
            "type" => $request->type,
            "expired_at" => now()->addMinute(5)
        ]);

        Mail::to($request->email)->queue(new OtpMail($otp));

        return response()->json([
            "message" => "Verify OTP successfully"
        ]);
    }

    public function verifiedRegisterOtp(OtpVerifyRequest $request) {
        $request->validated();
        $otpRecord = Otp::where("email", $request->email)
            ->where("type", "register")
            ->first();
    }
}