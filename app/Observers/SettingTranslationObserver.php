<?php

namespace App\Observers;

use A17\Twill\Models\Translations\SettingTranslation;
use App\Models\Cache;

class SettingTranslationObserver
{
    public function saved(SettingTranslation $settingTranslation)
    {
        $this->invalidateSettingsCache();
    }

    public function deleted(SettingTranslation $settingTranslation)
    {
        $this->invalidateSettingsCache();
    }

    private function invalidateSettingsCache(): void
    {
        $cache = Cache::query()->first();

        if ($cache) {
            $cache->update([
                'cache' => uniqid(),
            ]);
        }
    }
}
