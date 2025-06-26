<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\RentalHistory;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function createInvoice(Request $request, $rentalId = null)
    {
        $tenant = Auth::user();

        $paymentMethod = $request->input('payment_method', config('services.tripay.default_method', 'QRIS'));

        $rental = $rentalId
            ? RentalHistory::with(['room.landboard'])->findOrFail($rentalId)
            : RentalHistory::with(['room.landboard'])->where('tenant_id', $tenant->tenant->id)->latest()->first();

        if (!$rental || !$rental->room || !$rental->room->landboard) {
            return redirect()->back()->with('error', 'Data sewa belum lengkap.');
        }

        $room = $rental->room;
        $landboard = $room->landboard;

        $baseAmount = $room->price * $rental->duration_months;
        $startDate = Carbon::parse($rental->start_date);
        $deadline = $startDate->copy()->addDays($landboard->late_fee_days ?? 0);

        $lateFee = 0;
        if ($landboard->is_penalty_enabled && now()->gt($deadline)) {
            $lateFee = $landboard->late_fee_amount ?? 0;
        }

        $totalAmount = $baseAmount + $lateFee;
        $amountInt = (int) $totalAmount;

        $orderId = config('services.tripay.merchant_ref_prefix', 'INV-') . strtoupper(Str::random(10));
        $dueDate = now()->addDays(7);

        $payment = Payment::updateOrCreate(
            [
                'rental_history_id' => $rental->id,
                'status'            => 'pending',
            ],
            [
                'tenant_id'      => $rental->tenant_id,
                'room_id'        => $room->id,
                'landboard_id'   => $landboard->id,
                'amount'         => $baseAmount,
                'penalty_amount' => $lateFee,
                'total_amount'   => $totalAmount,
                'invoice_id'     => $orderId,
                'due_date'       => $dueDate,
                'paid_at'        => null,
            ]
        );

        $merchantCode = config('services.tripay.merchant_code');
        $privateKey   = config('services.tripay.private_key');
        $apiKey       = config('services.tripay.api_key');

        $signature = hash_hmac(
            'sha256',
            $merchantCode . $orderId . $amountInt,
            $privateKey
        );

        $isProduction = config('services.tripay.is_production', false);
        $tripayUrl = $isProduction
            ? 'https://tripay.co.id/api/transaction/create'
            : 'https://tripay.co.id/api-sandbox/transaction/create';

        $requestData = [
            'method'         => $paymentMethod,
            'merchant_ref'   => $orderId,
            'amount'         => $amountInt,
            'customer_name'  => $tenant->name,
            'customer_email' => $tenant->email,
            'order_items'    => [
                [
                    'name'     => 'Sewa kamar ' . $room->room_number,
                    'price'    => $amountInt,
                    'quantity' => 1
                ]
            ],
            'callback_url'   => url('/tripay/callback'),
            'return_url'     => route('tenant.dashboard.index'),
            'expired_time'   => $dueDate->timestamp,
            'signature'      => $signature,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey
        ])->post($tripayUrl, $requestData);

        $responseData = $response->json();

        if ($response->successful() && isset($responseData['data']['checkout_url'])) {
            $payment->update(['external_id' => $responseData['data']['reference']]);
            return redirect()->away($responseData['data']['checkout_url']);
        }

        $errorMessage = $responseData['message'] ?? 'Gagal membuat invoice';
        return redirect()->back()->with('error', $errorMessage);
    }


    public function handleTripayCallback(Request $request)
    {
        $callbackSignature = $request->header('X-Callback-Signature');
        $privateKey = config('services.tripay.private_key');
        
        if (!$callbackSignature) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $json = $request->getContent();
        $computedSignature = hash_hmac('sha256', $json, $privateKey);
        
        if (!hash_equals($callbackSignature, $computedSignature)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $status = $request->input('status');
        $reference = $request->input('reference');
        $merchantRef = $request->input('merchant_ref');

        if ($status === 'PAID') {
            $payment = Payment::where('external_id', $reference)
                            ->orWhere('invoice_id', $merchantRef)
                            ->first();

            if ($payment && $payment->status !== 'paid') {
                $payment->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                ]);
            } 
        } 
        return response()->json(['success' => true], 200);
    }

    public function checkStatus()
    {
        $tenant = Auth::user();
        $rental = RentalHistory::where('tenant_id', $tenant->tenant->id)->latest()->first();

        if (!$rental) {
            return response()->json(['status' => 'no-rental']);
        }

        $payment = Payment::where('rental_history_id', $rental->id)->latest()->first();

        return response()->json([
            'status'     => $payment ? $payment->status : 'not-found',
            'invoice_id' => $payment->invoice_id ?? null,
            'paid_at'    => $payment->paid_at ?? null,
        ]);
    }
}
