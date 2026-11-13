<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $setting) {
            Cache::forget("app_setting_{$setting->key}");
            Cache::forget('all_app_settings');
        });

        static::deleted(function (self $setting) {
            Cache::forget("app_setting_{$setting->key}");
            Cache::forget('all_app_settings');
        });
    }

    /**
     * Dapatkan nilai pengaturan berdasarkan key dengan cache.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("app_setting_{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            if (! $setting || $setting->value === null) {
                return $default;
            }

            return match ($setting->type) {
                'number' => is_numeric($setting->value) ? (str_contains((string) $setting->value, '.') ? (float) $setting->value : (int) $setting->value) : $default,
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'json' => json_decode($setting->value, true) ?: $default,
                default => $setting->value,
            };
        });
    }

    /**
     * Simpan atau perbarui nilai pengaturan.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string'): void
    {
        $serializedValue = match ($type) {
            'json' => is_array($value) ? json_encode($value) : $value,
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };

        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $serializedValue,
                'group' => $group,
                'type' => $type,
            ]
        );

        Cache::forget("app_setting_{$key}");
        Cache::forget('all_app_settings');
    }

    /**
     * Dapatkan seluruh setting dalam bentuk array asosiatif [key => value].
     *
     * @return array<string, mixed>
     */
    public static function allAsArray(): array
    {
        return Cache::rememberForever('all_app_settings', function () {
            return static::pluck('value', 'key')->toArray();
        });
    }
}
