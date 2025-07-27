<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EsewaController extends Controller
{
    public function initiatePayment(Request $request)
    {
        $amount = 1000; // Test amount (in NPR)
        $transaction_uuid = now()->timestamp; // Unique transaction ID
        $merchant_code = env('ESEWA_MERCHANT_CODE', 'EPAYTEST');
        $secret_key = env('ESEWA_SECRET_KEY', '8gBm/:&EnhH.1/q');

        // Generate HMAC-SHA256 signature
        $message = "total_amount=$amount,transaction_uuid=$transaction_uuid,product_code=$merchant_code";
        $signature = base64_encode(hash_hmac('sha256', $message, $secret_key, true));

        // Data for eSewa form
        $data = [
            'amount' => $amount,
            'tax_amount' => 0,
            'total_amount' => $amount,
            'transaction_uuid' => $transaction_uuid,
            'product_code' => $merchant_code,
            'product_service_charge' => 0,
            'product_delivery_charge' => 0,
            'success_url' => route('esewa.success'),
            'failure_url' => route('esewa.failure'),
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
            'signature' => $signature,
        ];

        return view('esewa.payment', compact('data'));
    }

    public function paymentSuccess(Request $request)
    {
        // Decode response from eSewa
        $response_data = json_decode(base64_decode($request->query('data')), true);

        if ($response_data && $response_data['status'] === 'COMPLETE') {
            // Verify payment with eSewa
            $url = "https://uat.esewa.com.np/api/epay/transaction/status/?product_code=EPAYTEST&total_amount={$response_data['total_amount']}&transaction_uuid={$response_data['transaction_uuid']}";
            $response = Http::get($url);

            if ($response->successful() && $response->json()['status'] === 'COMPLETE') {
                // Update your order status here
                return redirect()->route('esewa.initiate')->with('message', 'Payment successful!');
            }
        }

        return redirect()->route('esewa.failure')->with('message', 'Payment verification failed.');
    }

    public function paymentFailure(Request $request)
    {
        return view('esewa.failure')->with('message', 'Payment failed or was canceled.');
    }
}
