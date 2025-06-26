<?php

namespace App\Http\Controllers;

use App\Models\Disbursement;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DisbursementController extends Controller
{
    public function __construct()
    {
        //\Xendit\Xendit::setApiKey(config('services.xendit.secret_key'));
    }


    public function createDisbursement($paymentId)
    {
        try {
            $payment = Payment::with(['landboard', 'tenant', 'room', 'rentalHistory'])
                ->where('id', $paymentId)
                ->where('status', 'paid')
                ->first();

            if (!$payment) {
                return response()->json(['error' => 'Payment tidak ditemukan atau belum dibayar'], 404);
            }

            if (Disbursement::where('payment_id', $paymentId)->exists()) {
                return response()->json(['message' => 'Disbursement sudah dibuat sebelumnya'], 200);
            }

            $landboard = User::where('id', $payment->landboard_id)
                ->where('role', 'landboard')
                ->first();

            if (!$landboard || !$landboard->bank_name || !$landboard->bank_account) {
                return response()->json(['error' => 'Data rekening landboard tidak lengkap'], 400);
            }

            $platformFeePercentage = 0.10;
            $disbursementAmount = (int) ($payment->total_amount * (1 - $platformFeePercentage));
            $platformFee = (int) ($payment->total_amount * $platformFeePercentage);

            $externalId = 'DISB-' . strtoupper(Str::random(10));

            $disbursementParams = [
                'external_id' => $externalId,
                'bank_code' => $this->getBankCode($landboard->bank_name),
                'account_holder_name' => $landboard->name,
                'account_number' => $landboard->bank_account,
                'description' => "Pembayaran sewa kamar {$payment->room->name} - {$payment->rentalHistory->start_date}",
                'amount' => $disbursementAmount,
                'email_to' => [$landboard->email],
            ];

            $disbursement = Disbursement::create($disbursementParams);

            $disbursementModel = Disbursement::create([
                'payment_id' => $payment->id,
                'landboard_id' => $landboard->id,
                'external_id' => $externalId,
                'xendit_id' => $disbursement['id'],
                'bank_code' => $disbursementParams['bank_code'],
                'bank_name' => $landboard->bank_name,
                'account_number' => $landboard->bank_account,
                'account_holder_name' => $landboard->name,
                'amount' => $disbursementAmount,
                'platform_fee' => $platformFee,
                'total_amount' => $payment->total_amount,
                'status' => $disbursement['status'] ?? 'PENDING',
                'description' => $disbursementParams['description'],
                'disbursed_at' => null,
                'failure_reason' => null,
            ]);

            return response()->json([
                'message' => 'Disbursement berhasil dibuat',
                'disbursement_id' => $disbursementModel->id,
                'external_id' => $externalId,
                'amount' => $disbursementAmount
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan sistem',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function handleDisbursementCallback(Request $request)
    {
        $callbackToken = $request->header('X-CALLBACK-TOKEN');

        if ($callbackToken !== config('services.xendit.callback_token')) {
            return response()->json(['message' => 'Invalid callback token'], 403);
        }

        $externalId = $request->input('external_id');
        $status = $request->input('status');
        $failureCode = $request->input('failure_code');

        if (!$externalId || !$status) {
            return response()->json(['message' => 'Missing required fields'], 422);
        }

        $disbursement = Disbursement::where('external_id', $externalId)->first();
        if (!$disbursement) {
            return response()->json(['message' => 'Disbursement not found'], 404);
        }

        $updateData = [
            'status' => $status,
            'failure_reason' => $failureCode,
        ];

        if ($status === 'COMPLETED') {
            $updateData['disbursed_at'] = now();
        }

        $disbursement->update($updateData);

        return response()->json(['message' => 'Callback handled successfully']);
    }

    public function getDisbursementStatus($paymentId)
    {
        $disbursement = Disbursement::where('payment_id', $paymentId)->first();

        if (!$disbursement) {
            return response()->json(['status' => 'not-found']);
        }

        return response()->json([
            'status' => $disbursement->status,
            'external_id' => $disbursement->external_id,
            'amount' => $disbursement->amount,
            'disbursed_at' => $disbursement->disbursed_at,
            'failure_reason' => $disbursement->failure_reason,
        ]);
    }

    public function processPaidPayments()
    {
        $paidPayments = Payment::where('status', 'paid')
            ->whereDoesntHave('disbursement')
            ->with(['landboard', 'room', 'rentalHistory'])
            ->get();

        $results = [];

        foreach ($paidPayments as $payment) {
            $result = $this->createDisbursement($payment->id);
            $results[] = [
                'payment_id' => $payment->id,
                'result' => $result->getData()
            ];
        }

        return response()->json([
            'message' => 'Batch disbursement processing completed',
            'processed_count' => count($paidPayments),
            'results' => $results
        ]);
    }

    private function getBankCode($bankName)
    {
        $bankCodes = [
            'BCA' => 'BCA',
            'BNI' => 'BNI',
            'BRI' => 'BRI',
            'MANDIRI' => 'MANDIRI',
            'CIMB' => 'CIMB',
            'DANAMON' => 'DANAMON',
            'PERMATA' => 'PERMATA',
            'BII' => 'BII',
            'PANIN' => 'PANIN',
            'MEGA' => 'MEGA',
            'NISP' => 'NISP',
            'MAYBANK' => 'MAYBANK',
            'BTPN' => 'BTPN',
            'JENIUS' => 'BTPN',
            'SUMUT' => 'SUMUT',
            'BANTEN' => 'BANTEN',
            'BJB' => 'BJB',
            'JATIM' => 'JATIM',
            'JATENG' => 'JATENG',
            'KALBAR' => 'KALBAR',
            'KALSEL' => 'KALSEL',
            'KALTIM' => 'KALTIM',
            'SULSEL' => 'SULSEL',
            'BSM' => 'BSM',
            'MUAMALAT' => 'MUAMALAT',
        ];

        $bankNameUpper = strtoupper($bankName);
        return $bankCodes[$bankNameUpper] ?? 'BCA';
    }
}
