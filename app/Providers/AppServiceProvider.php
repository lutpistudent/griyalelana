<?php

namespace App\Providers;

use App\Database\Connectors\NeonPostgresConnector;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('db.connector.pgsql', fn () => new NeonPostgresConnector());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        // Share settings globally so the footer always has contact info
        // Uses cached settings (1 query + cache) instead of 7 individual queries
        View::composer('layouts.app', function ($view) {
            if (!isset($view->getData()['settings'])) {
                $all = Setting::getAllCached();
                $view->with('settings', [
                    'kost_name' => $all['kost_name'] ?? 'Griya Lelana',
                    'kost_tagline' => $all['kost_tagline'] ?? 'Hunian Nyaman, Harga Terjangkau',
                    'kost_description' => $all['kost_description'] ?? '',
                    'kost_address' => $all['kost_address'] ?? '',
                    'whatsapp_number' => $all['whatsapp_number'] ?? '',
                    'email' => $all['email'] ?? '',
                    'google_maps_embed' => $all['google_maps_embed'] ?? '',
                ]);
            }
        });
    }
}
