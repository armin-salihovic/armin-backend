<?php

namespace App\Services;

use A17\Twill\Models\Setting;

class Settings
{
    private $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function __get($key)
    {
        if (isset($this->settings[$key])) {
            return $this->settings[$key];
        }

        return null;
    }

    public function image($key, $crop = 'default'): ?string
    {
        $setting = Setting::where('section', 'settings')->where('key', $key)->first();
        return $setting !== null && $setting->hasImage($key, $crop) ? $setting->image($key, $crop, ['fm' => null]) : null;
    }

    public function socialImage($key, $crop = 'default'): ?string
    {
        $setting = Setting::where('section', 'settings')->where('key', $key)->first();
        return $setting !== null && $setting->hasImage($key, $crop) ? $setting->socialImage($key, $crop, ['fm' => null]) : null;
    }
}
