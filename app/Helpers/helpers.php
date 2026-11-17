<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Ambil nilai pengaturan dari tabel settings.
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

