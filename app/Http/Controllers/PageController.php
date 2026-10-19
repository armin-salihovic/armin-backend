<?php

namespace App\Http\Controllers;

use A17\Twill\Models\Setting;
use App\Http\Resources\PageResource;
use App\Http\Resources\SettingsResource;
use App\Repositories\PageRepository;
use App\Services\Settings;

class PageController extends Controller
{
    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function show($slug)
    {
        $page = $this->repository->forSlug($slug);

        if ($page === null || ! $page->hasActiveTranslation(request()->attributes->get('locale', 'en'))) {
            return response()->json([], 404);
        }

        return new PageResource($page);
    }

    public function getSettings()
    {
        $locale = request()->attributes->get('locale', 'en');
        $fallbackLocale = config('translatable.fallback_locale', 'en');
        $locales = array_values(array_unique([$locale, $fallbackLocale]));

        $settings = Setting::where('section', 'settings')
            ->with(['translations' => function ($query) use ($locales) {
                $query->whereIn('locale', $locales)->where('active', true);
            }])
            ->orderBy('id')
            ->get()
            ->mapWithKeys(function ($setting) use ($locale, $fallbackLocale) {
                $translation = $setting->translations->firstWhere('locale', $locale);
                $value = $translation ? $translation->value : null;

                if ($value === null || $value === '') {
                    $fallback = $setting->translations->firstWhere('locale', $fallbackLocale);
                    $value = $fallback ? $fallback->value : null;
                }

                return [$setting->key => $value];
            });

        return new SettingsResource(new Settings($settings));
    }
}
