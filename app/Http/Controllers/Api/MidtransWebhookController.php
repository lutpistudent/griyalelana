<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    /**
     * Handle Midtrans payment notification webhook.
     *
     * POST /api/midtrans/webhook
     */
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans webhook received', $payload);

        // Verify server key
        $serverKey = config('midtrans.server_key');
        $signatureKey = $payload['signature_key'] ?? '';

        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            Log::warning('Midtrans webhook signature mismatch', [
                'order_id' => $orderId,
                'expected' => $expectedSignature,
                'received' => $signatureKey,
            ]);
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
        }

        $service = new MidtransService();
        $success = $service->handleNotification($payload);

        if ($success) {
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
    }
}
