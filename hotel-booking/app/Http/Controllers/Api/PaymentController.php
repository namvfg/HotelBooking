<?php

namespace App\Http\Controllers\Api;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\Payment\PaymentDetailResouce;
use App\Http\Resources\Payment\PaymentResouce;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PaymentResouce::collection(
            Payment::paginate(config("pagination.per_page")),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        return (new PaymentDetailResouce($payment))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function createVnpay(Request $request, Payment $payment)
    {
        $user = $request->user();

        // Authorize
        if (
            $user->role !== Role::ADMIN &&
            $payment->booking->user_id !== $user->id
        ) {
            abort(403);
        }

        if ($payment->status === PaymentStatus::SUCCESS->value) {
            return response()->json([
                'message' => 'Payment already completed'
            ], 400);
        }

        $payment->load('booking');

        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config('vnpay.url');
        $vnp_Returnurl = config('vnpay.return_url');

        $vnp_TxnRef = $payment->id;
        $vnp_Amount = $payment->amount * 100;
        $vnp_OrderInfo = "Thanh toan booking #" . $payment->booking_id;

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $request->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);

        $hashData = "";
        $query = "";

        foreach ($inputData as $key => $value) {
            if ($value !== "" && $value !== null) {

                $encodedValue = urlencode($value);

                $hashData .= $key . "=" . $encodedValue . "&";
                $query .= $key . "=" . $encodedValue . "&";
            }
        }

        $hashData = rtrim($hashData, "&");
        $query = rtrim($query, "&");

        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $paymentUrl = $vnp_Url . '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        return response()->json([
            'payment_url' => $paymentUrl
        ]);
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.hash_secret');
        $frontendUrl = config('services.frontend.url');

        $inputData = [];

        foreach ($request->query() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? null;

        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);

        $hashData = "";

        foreach ($inputData as $key => $value) {
            if ($value !== "" && $value !== null) {
                $hashData .= $key . "=" . urlencode($value) . "&";
            }
        }

        $hashData = rtrim($hashData, "&");

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if (!hash_equals($secureHash, $vnp_SecureHash)) {
            return redirect()->away("{$frontendUrl}/payment-failed");
        }

        $payment = Payment::with('booking')->find($request->vnp_TxnRef);

        if (!$payment) {
            return redirect()->away("{$frontendUrl}/payment-failed");
        }

        // Dùng transaction để đảm bảo đồng bộ
        DB::transaction(function () use ($request, $payment) {

            if ($request->vnp_ResponseCode === "00") {

                if ($payment->status !== PaymentStatus::SUCCESS->value) {

                    $payment->update([
                        'status' => PaymentStatus::SUCCESS->value,
                        'paid_at' => now(),
                        'transaction_code' => $request->vnp_TransactionNo ?? null,
                    ]);

                    if ($payment->booking) {
                        $payment->booking->update([
                            'status' => BookingStatus::CONFIRMED->value,
                        ]);
                    }
                }
            } else {

                $payment->update([
                    'status' => PaymentStatus::FAILED->value,
                ]);

                if ($payment->booking) {
                    $payment->booking->update([
                        'status' => BookingStatus::CANCELED->value,
                    ]);
                }
            }
        });

        if ($request->vnp_ResponseCode === "00") {
            return redirect()->away(
                "{$frontendUrl}/payment-success?payment_id={$payment->id}"
            );
        }

        return redirect()->away(
            "{$frontendUrl}/payment-failed?payment_id={$payment->id}"
        );
    }
}
