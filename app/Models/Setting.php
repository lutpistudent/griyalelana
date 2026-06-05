<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Cache key for all settings.
     */
    private const CACHE_KEY = 'app_settings';
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Get all settings as a cached key-value array.
     * Reduces 7+ individual queries to 1 query + cache hit.
     */
    public static function getAllCached(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get a single setting value (uses cached collection).
     */
    public static function getValue(string $key, $default = null)
    {
        $settings = self::getAllCached();

        if (!isset($settings[$key])) {
            return $default;
        }

        $value = $settings[$key];

        // Determine type from DB only if needed (fallback)
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $value;
        }

        return match ($setting->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            'integer' => (int) $value,
            default => $value,
        };
    }

    /**
     * Set a setting value and invalidate cache.
     */
    public static function setValue(string $key, $value, string $type = 'string', string $description = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'type' => $type,
                'description' => $description,
            ]
        );

        // Invalidate cache so next request gets fresh data
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Clear the settings cache manually.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
