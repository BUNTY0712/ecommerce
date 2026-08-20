<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Get all settings as an associative key-value array from cache.
     */
    public static function getAll(): array
    {
        return Cache::rememberForever('site_settings_map', function () {
            try {
                return static::pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /**
     * Get a setting by key.
     */
    public static function get(string $key, $default = null)
    {
        $settings = static::getAll();
        return $settings[$key] ?? $default;
    }

    /**
     * Set/update a setting by key.
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget('site_settings_map');
    }

    /**
     * Clear settings cache manually.
     */
    public static function clearCache(): void
    {
        Cache::forget('site_settings_map');
    }
}
