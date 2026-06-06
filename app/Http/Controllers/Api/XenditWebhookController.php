<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    public function handle(Request $request, XenditService $xendit)
    {
        $callbackToken = config('xendit.callback_token');
        $receivedToken = $request->header('X-CALLBACK-TOKEN');

        if (filled($callbackToken) && ! hash_equals($callbackToken, (string) $receivedToken)) {
            Log::warning('Xendit webhook callback token mismatch');

            return response()->json(['status' => 'error', 'message' => 'Invalid callback token'], 403);
        }

        if (blank($callbackToken) && config('xendit.is_production')) {
            Log::warning('Xendit webhook rejected because callback token is empty in production');

            return response()->json(['status' => 'error', 'message' => 'Callback token is not configured'], 403);
        }

        $payload = $request->all();

        Log::info('Xendit webhook received', [
            'invoice_id' => $payload['id'] ?? null,
            'external_id' => $payload['external_id'] ?? null,
            'status' => $payload['status'] ?? null,
        ]);

        if ($xendit->handleInvoiceWebhook($payload)) {
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
    }
}
