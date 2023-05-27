<?php

namespace App\Observers;

use A17\Twill\Models\Translations\SettingTranslation;
use App\Models\Cache;

class SettingTranslationObserver
{
    public function updated(SettingTranslation $settingTranslation)
    {
        $cache = Cache::first();
        $cache->update([
            'cache' => uniqid(),
        ]);
    }
}
