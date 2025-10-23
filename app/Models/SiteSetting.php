<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllSettings(): array
    {
        return Cache::remember('site_settings', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get a specific setting value
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = self::getAllSettings();
        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, mixed $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        Cache::forget('site_settings');
    }

    /**
     * Get settings by group
     */
    public static function getByGroup(string $group): array
    {
        return Cache::remember("site_settings_group_{$group}", 3600, function () use ($group) {
            return self::where('group', $group)
                ->orderBy('order')
                ->get()
                ->mapWithKeys(fn ($setting) => [$setting->key => $setting->value])
                ->toArray();
        });
    }

    /**
     * Clear cache when model is saved or deleted
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('site_settings');
            Cache::flush(); // Clear all group caches
        });

        static::deleted(function () {
            Cache::forget('site_settings');
            Cache::flush();
        });
    }
}
