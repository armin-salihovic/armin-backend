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

    function show($slug)
    {
        $project = $this->repository->forSlug($slug);

        if ($project === null) return response(null, 404);

        return new PageResource($project);
    }

    function getSettings()
    {
        $settings = Setting::where('section', 'settings')
            ->join('setting_translations', 'settings.id', '=', 'setting_translations.setting_id')
            ->pluck('value', 'key');

        return new SettingsResource(new Settings($settings));
    }
}
