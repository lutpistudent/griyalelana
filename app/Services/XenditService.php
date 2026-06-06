<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentSchedule;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class XenditService
{
    public function createInvoice(PaymentSchedule $schedule): array
    {
        if ($schedule->status === 'paid') {
            throw new RuntimeException('Tagihan ini sudah lunas.');
        }

        $secretKey = config('xendit.secret_key');

        if (blank($secretKey)) {
            throw new RuntimeException('XENDIT_SECRET_KEY belum dikonfigurasi.');
        }

        $schedule->loadMissing('contract.user', 'contract.room.roomType', 'contract.booking');

        $existingPayment = Payment::query()
            ->where('payment_schedule_id', $schedule->id)
            ->where('status', 'pending')
            ->whereNotNull('xendit_invoice_url')
            ->latest()
            ->first();

        if ($existingPayment) {
            return [
                'success' => true,
                'invoice_url' => $existingPayment->xendit_invoice_url,
                'external_id' => $existingPayment->xendit_external_id ?? $existingPayment->midtrans_order_id,
                'payment' => $existingPayment,
            ];
        }

        $contract = $schedule->contract;
        $user = $contract->user;
        $amount = (int) round((float) $schedule->amount);
        $externalId = 'GRL-' . $schedule->id . '-' . Str::upper(Str::random(8));

        $payload = [
            'external_id' => $externalId,
            'amount' => $amount,
            'description' => $this->descriptionFor($schedule),
            'invoice_duration' => $this->invoiceDurationFor($schedule),
            'currency' => 'IDR',
            'should_send_email' => (bool) config('xendit.should_send_email', false),
            'success_redirect_url' => config('xendit.success_redirect_url') ?: route('dashboard'),
            'failure_redirect_url' => config('xendit.failure_redirect_url') ?: route('dashboard'),
            'customer' => array_filter([
                'given_names' => $user->name,
                'email' => $user->email,
                'mobile_number' => $user->phone ?? null,
            ]),
            'items' => [
                [
                    'name' => $this->descriptionFor($schedule),
                    'quantity' => 1,
                    'price' => $amount,
                    'category' => 'Sewa Kamar',
                ],
            ],
            'metadata' => [
                'payment_schedule_id' => $schedule->id,
                'contract_id' => $contract->id,
                'user_id' => $user->id,
                'installment_type' => $schedule->installment_type,
            ],
        ];

        $response = Http::withBasicAuth($secretKey, '')
            ->acceptJson()
            ->asJson()
            ->timeout(20)
            ->post(rtrim(config('xendit.api_base_url'), '/') . '/v2/invoices', $payload);

        if ($response->failed()) {
            Log::error('Xendit invoice creation failed', [
                'payment_schedule_id' => $schedule->id,
                'status' => $response->status(),
                'body' => Str::limit($response->body(), 500),
            ]);

            throw new RuntimeException($this->messageFromResponse($response));
        }

        $data = $response->json();

        $payment = Payment::create([
            'payment_schedule_id' => $schedule->id,
            'user_id' => $user->id,
            'contract_id' => $contract->id,
            'amount' => $schedule->amount,
            'payment_method' => null,
            'midtrans_order_id' => $externalId,
            'xendit_external_id' => $externalId,
            'xendit_invoice_id' => $data['id'] ?? null,
            'xendit_invoice_url' => $data['invoice_url'] ?? null,
            'xendit_status' => $data['status'] ?? 'PENDING',
            'xendit_payload' => $data,
            'status' => 'pending',
            'receipt_url' => $data['invoice_url'] ?? null,
        ]);

        return [
            'success' => true,
            'invoice_url' => $data['invoice_url'] ?? null,
            'external_id' => $externalId,
            'payment' => $payment,
        ];
    }

    public function handleInvoiceWebhook(array $payload): bool
    {
        $externalId = $payload['external_id'] ?? null;
        $invoiceId = $payload['id'] ?? null;

        if (blank($externalId) && blank($invoiceId)) {
            return false;
        }

        $payment = Payment::query()
            ->when($externalId, function ($query) use ($externalId) {
                $query->where('xendit_external_id', $externalId)
                    ->orWhere('midtrans_order_id', $externalId);
            })
            ->when($invoiceId, fn ($query) => $query->orWhere('xendit_invoice_id', $invoiceId))
            ->first();

        if (! $payment) {
            return false;
        }

        if ($payment->status === 'success') {
            return true;
        }

        $xenditStatus = strtoupper($payload['status'] ?? '');
        $status = match ($xenditStatus) {
            'PAID', 'SETTLED' => 'success',
            'EXPIRED' => 'expired',
            'FAILED' => 'failed',
            default => 'pending',
        };

        DB::transaction(function () use ($payment, $payload, $status, $xenditStatus) {
            $paidAt = $status === 'success'
                ? $this->paidAtFromPayload($payload)
                : null;

            $payment->update([
                'status' => $status,
                'payment_method' => $this->paymentMethodFromPayload($payload),
                'xendit_payment_id' => $payload['payment_id'] ?? null,
                'xendit_status' => $xenditStatus,
                'xendit_payload' => $payload,
                'paid_at' => $paidAt,
            ]);

            if ($status === 'success' && $payment->paymentSchedule) {
                $payment->paymentSchedule->update([
                    'status' => 'paid',
                    'paid_at' => $paidAt ?? now(),
                ]);
            }
        });

        return true;
    }

    protected function descriptionFor(PaymentSchedule $schedule): string
    {
        $type = match ($schedule->installment_type) {
            'dp' => 'DP 30%',
            'checkin_payment' => 'Pembayaran Check-in',
            'final_payment' => 'Pelunasan',
            'installment' => 'Cicilan',
            default => ucwords(str_replace('_', ' ', $schedule->installment_type)),
        };

        return "{$type} - {$schedule->contract->contract_number}";
    }

    protected function invoiceDurationFor(PaymentSchedule $schedule): int
    {
        $booking = $schedule->contract->booking;

        if ($schedule->installment_type === 'dp' && $booking?->dp_expires_at) {
            return max(300, now()->diffInSeconds($booking->dp_expires_at, false));
        }

        return (int) config('xendit.invoice_duration', 86400);
    }

    protected function paidAtFromPayload(array $payload): Carbon
    {
        try {
            return isset($payload['paid_at'])
                ? Carbon::parse($payload['paid_at'])
                : now();
        } catch (Throwable) {
            return now();
        }
    }

    protected function paymentMethodFromPayload(array $payload): ?string
    {
        return $payload['payment_method']
            ?? $payload['payment_channel']
            ?? $payload['ewallet_type']
            ?? $payload['bank_code']
            ?? null;
    }

    protected function messageFromResponse(Response $response): string
    {
        $message = $response->json('message')
            ?? $response->json('error_code')
            ?? 'Gagal membuat invoice Xendit.';

        return Str::limit((string) $message, 250);
    }
}
