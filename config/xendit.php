<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Xendit Configuration
    |--------------------------------------------------------------------------
    |
    | API key dan callback token disimpan di .env. Invoice Xendit dibuat lewat
    | endpoint /v2/invoices dan webhook invoice diterima di /api/xendit/webhook.
    |
    */

    'secret_key' => env('XENDIT_SECRET_KEY', ''),
    'callback_token' => env('XENDIT_CALLBACK_TOKEN', ''),
    'is_production' => env('XENDIT_IS_PRODUCTION', false),
    'api_base_url' => env('XENDIT_API_BASE_URL', 'https://api.xendit.co'),
    'invoice_duration' => env('XENDIT_INVOICE_DURATION', 86400),
    'success_redirect_url' => env('XENDIT_SUCCESS_REDIRECT_URL'),
    'failure_redirect_url' => env('XENDIT_FAILURE_REDIRECT_URL'),
    'should_send_email' => env('XENDIT_SHOULD_SEND_EMAIL', false),
];
