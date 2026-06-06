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
    putenv("LARAVEL_STORAGE_PATH={$storagePath}");

    foreach ([
        $storagePath.'/app/public',
        $storagePath.'/bootstrap/cache',
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

    if ($isVercel) {
        foreach ([
            'APP_CONFIG_CACHE' => $storagePath.'/bootstrap/cache/config.php',
            'APP_EVENTS_CACHE' => $storagePath.'/bootstrap/cache/events.php',
            'APP_PACKAGES_CACHE' => $storagePath.'/bootstrap/cache/packages.php',
            'APP_ROUTES_CACHE' => $storagePath.'/bootstrap/cache/routes.php',
            'APP_SERVICES_CACHE' => $storagePath.'/bootstrap/cache/services.php',
            'VIEW_COMPILED_PATH' => $storagePath.'/framework/views',
        ] as $key => $path) {
            $_ENV[$key] = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $path;
            $_SERVER[$key] = $_ENV[$key];
            putenv("{$key}={$_ENV[$key]}");
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
