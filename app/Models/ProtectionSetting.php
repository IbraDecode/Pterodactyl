<?php

namespace Pterodactyl\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProtectionSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'description'];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value, $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'description' => $description]
        );
    }

    public static function getAdminIds(): array
    {
        $ids = self::get('admin_ids', '1');
        return array_map('intval', explode(',', $ids));
    }

    public static function getAccessDeniedMessage(): string
    {
        return self::get('access_denied_message', 'Akses ditolak: Anda tidak memiliki izin untuk mengakses fitur ini.');
    }

    public static function isProtectionEnabled(string $feature): bool
    {
        return self::get("protection_{$feature}", 'true') === 'true';
    }
}
