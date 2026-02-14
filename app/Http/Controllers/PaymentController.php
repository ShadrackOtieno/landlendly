<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function initiateMpesa(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'subscription_plan_id' => 'required|exists:subscription_plans,id'
        ]);

        // Ensure phone format is 2547XXXXXXXX
        $phone = preg_replace('/^0/', '254', $validated['phone']);

        $transactionId = 'TXN' . Str::upper(Str::random(10));

        $payment = Payment::create([
            'user_id' => Auth::id(),
            'transaction_id' => $transactionId,
            'amount' => $validated['amount'],
            'phone_number' => $phone,
            'status' => 'pending',
            'description' => 'Subscription payment'
        ]);

        /*
    |--------------------------------------------------------------------------
    | 1. Generate Access Token
    |--------------------------------------------------------------------------
    */
        $credentials = base64_encode(env('MPESA_CONSUMER_KEY') . ':' . env('MPESA_CONSUMER_SECRET'));

        $tokenResponse = Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials,
        ])->get('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');

        $accessToken = $tokenResponse['access_token'];

        /*
    |--------------------------------------------------------------------------
    | 2. Generate STK Password
    |--------------------------------------------------------------------------
    */
        $timestamp = now()->format('YmdHis');

        $password = base64_encode(
            env('MPESA_SHORTCODE') .
                env('MPESA_PASSKEY') .
                $timestamp
        );

        /*
    |--------------------------------------------------------------------------
    | 3. Send STK Push Request
    |--------------------------------------------------------------------------
    */
        $stkResponse = Http::withToken($accessToken)->post(
            'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
            [
                "BusinessShortCode" => env('MPESA_SHORTCODE'),
                "Password" => $password,
                "Timestamp" => $timestamp,
                "TransactionType" => "CustomerPayBillOnline",
                "Amount" => $validated['amount'],
                "PartyA" => $phone,
                "PartyB" => env('MPESA_SHORTCODE'),
                "PhoneNumber" => $phone,
                "CallBackURL" => env('MPESA_CALLBACK_URL'),
                "AccountReference" => $transactionId,
                "TransactionDesc" => "Subscription Payment"
            ]
        );

        Log::info('STK Response:', $stkResponse->json());

        return response()->json([
            'success' => true,
            'transaction_id' => $transactionId,
            'stk_response' => $stkResponse->json()
        ]);
    }

    // private function simulatePaymentCallback($payment, $planId)
    // {
    //     $payment->update([
    //         'status' => 'completed',
    //         'mpesa_receipt' => 'MPX' . Str::upper(Str::random(8))
    //     ]);

    //     $plan = SubscriptionPlan::find($planId);

    //     Subscription::create([
    //         'user_id' => $payment->user_id,
    //         'subscription_plan_id' => $planId,
    //         'starts_at' => Carbon::now(),
    //         'ends_at' => Carbon::now()->addDays($plan->billing_cycle_days),
    //         'status' => 'active'
    //     ]);
    // }



    public function callback(Request $request)
    {
        Log::info('M-Pesa Callback Received:', $request->all());

        $callback = $request->input('Body.stkCallback');

        if (!$callback) {
            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Invalid Callback Format'
            ]);
        }

        $resultCode = $callback['ResultCode'];
        $resultDesc = $callback['ResultDesc'];

        if ($resultCode == 0) {

            $items = collect($callback['CallbackMetadata']['Item']);

            $amount = $items->firstWhere('Name', 'Amount')['Value'] ?? null;
            $mpesaReceipt = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
            $phone = $items->firstWhere('Name', 'PhoneNumber')['Value'] ?? null;

            // Find the latest pending payment for that phone
            $payment = Payment::where('phone_number', $phone)
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'mpesa_receipt' => $mpesaReceipt,
                ]);
            }
        } else {

            // Payment failed
            $payment = Payment::where('status', 'pending')
                ->latest()
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => 'failed'
                ]);
            }
        }

        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Callback received successfully'
        ]);
    }
}
