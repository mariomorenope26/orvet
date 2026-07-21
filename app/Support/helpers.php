<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Acceso rápido a los ajustes globales del sitio (fila única cacheada).
     */
    function setting(?string $key = null, $default = null)
    {
        $settings = once(fn () => Setting::current());

        if ($key === null) {
            return $settings;
        }

        return $settings->{$key} ?? $default;
    }
}

if (! function_exists('media_url')) {
    /**
     * Devuelve la URL pública de un archivo almacenado en el disco "public".
     */
    function media_url(?string $path, ?string $fallback = null): ?string
    {
        if (blank($path)) {
            return $fallback;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
