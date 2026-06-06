<?php

use App\Http\Middleware\EnsureOwner;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$isVercel = (bool) ($_ENV['VERCEL'] ?? $_SERVER['VERCEL'] ?? getenv('VERCEL'));
$configuredStoragePath = $_ENV['LARAVEL_STORAGE_PATH']
    ?? $_SERVER['LARAVEL_STORAGE_PATH']
    ?? getenv('LARAVEL_STORAGE_PATH')
    ?: null;

if ($isVercel && ! $configuredStoragePath) {
    $configuredStoragePath = '/tmp/storage';
}

$storagePath = $configuredStoragePath;

if ($storagePath) {
    $_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;
    $_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;

    foreach ([
        $storagePath.'/app/public',
        $storagePath.'/framework/cache/data',
        $storagePath.'/framework/sessions',
        $storagePath.'/framework/testing',
        $storagePath.'/framework/views',
        $storagePath.'/logs',
    ] as $directory) {
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new RuntimeException("Directory [{$directory}] could not be created.");
        }
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'owner' => EnsureOwner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
