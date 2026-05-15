<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'label', 'description'];

    /**
     * Get a setting value by key.
     * Results are cached for 10 minutes to avoid hitting the DB on every request.
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = 'setting_' . $key;

        $setting = Cache::remember($cacheKey, 600, function () use ($key) {
            return static::where('key', $key)->first();
        });

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'integer' => (int) $setting->value,
            'boolean' => (bool) $setting->value,
            default   => $setting->value,
        };
    }

    /**
     * Set a setting value by key and bust its cache.
     */
    public static function set(string $key, $value): void
    {
        // Only update the value; preserve type, label, description set by migrations.
        $setting = static::where('key', $key)->first();
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            static::create(['key' => $key, 'value' => $value]);
        }
        Cache::forget('setting_' . $key);
    }

    /**
     * Get all settings as key => value map.
     */
    public static function allAsMap(): array
    {
        return static::all()->mapWithKeys(function ($setting) {
            $value = match ($setting->type) {
                'integer' => (int) $setting->value,
                'boolean' => (bool) $setting->value,
                default   => $setting->value,
            };
            return [$setting->key => $value];
        })->toArray();
    }
}
