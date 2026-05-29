<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class DeterministicEncrypt implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (empty($value)) {
            return null;
        }

        $cipher = 'AES-256-CBC';
        $keyStr = config('app.key');
        if (str_starts_with($keyStr, 'base64:')) {
            $keyStr = base64_decode(substr($keyStr, 7));
        }
        $iv = substr(hash('sha256', $keyStr . 'deterministic_iv'), 0, 16);
        
        $decrypted = openssl_decrypt($value, $cipher, $keyStr, 0, $iv);
        return $decrypted !== false ? $decrypted : $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (empty($value)) {
            return null;
        }

        return self::encrypt($value);
    }

    /**
     * Helper for manual encryption (e.g. for searching).
     */
    public static function encrypt($value)
    {
        if (empty($value)) return null;

        $cipher = 'AES-256-CBC';
        $keyStr = config('app.key');
        if (str_starts_with($keyStr, 'base64:')) {
            $keyStr = base64_decode(substr($keyStr, 7));
        }
        $iv = substr(hash('sha256', $keyStr . 'deterministic_iv'), 0, 16);
        
        return openssl_encrypt($value, $cipher, $keyStr, 0, $iv);
    }
}
