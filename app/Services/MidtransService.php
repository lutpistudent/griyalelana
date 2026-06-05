<?php

namespace App\Services;

use App\Models\PaymentSchedule;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create a Snap payment token for a payment schedule.
     */
    public function createTransaction(PaymentSchedule $schedule): array
    {
        $contract = $schedule->contract;
        $user = $contract->user;

        $orderId = 'GRL-' . $schedule->id . '-' . Str::random(6);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $schedule->amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => 'PSC-' . $schedule->id,
                    'price' => (int) $schedule->amount,
                    'quantity' => 1,
                    'name' => ucwords(str_replace('_', ' ', $schedule->installment_type))
                        . ' — ' . $contract->contract_number,
                ],
            ],
            'callbacks' => [
                'finish' => route('dashboard'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Create a pending payment record
            Payment::create([
                'payment_schedule_id' => $schedule->id,
                'user_id' => $user->id,
                'contract_id' => $contract->id,
                'amount' => $schedule->amount,
                'midtrans_order_id' => $orderId,
                'status' => 'pending',
            ]);

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle Midtrans webhook notification.
     */
    public function handleNotification(array $notification): bool
    {
        $orderId = $notification['order_id'] ?? null;
        $transactionStatus = $notification['transaction_status'] ?? null;
        $transactionId = $notification['transaction_id'] ?? null;
        $paymentType = $notification['payment_type'] ?? null;
        $fraudStatus = $notification['fraud_status'] ?? null;

        if (!$orderId) {
            return false;
        }

        $payment = Payment::where('midtrans_order_id', $orderId)->first();

        if (!$payment) {
            return false;
        }

        // Idempotency: skip if already successfully processed
        if ($payment->status === 'success') {
            return true;
        }

        // Determine payment status
        if ($transactionStatus === 'capture') {
            $status = ($fraudStatus === 'accept') ? 'success' : 'pending';
        } elseif ($transactionStatus === 'settlement') {
            $status = 'success';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $status = 'failed';
        } elseif ($transactionStatus === 'pending') {
            $status = 'pending';
        } else {
            $status = 'unknown';
        }

        // Update payment
        $payment->update([
            'status' => $status,
            'midtrans_transaction_id' => $transactionId,
            'payment_method' => $paymentType,
            'paid_at' => $status === 'success' ? now() : null,
        ]);

        // If payment is successful, update payment schedule
        if ($status === 'success') {
            $schedule = $payment->paymentSchedule;
            if ($schedule) {
                $schedule->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
        }

        return true;
    }
}
