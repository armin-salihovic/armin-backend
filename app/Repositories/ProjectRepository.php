<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleBlocks;
use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleSlugs;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Project;

class ProjectRepository extends ModuleRepository
{
    use HandleBlocks, HandleSlugs, HandleMedias, HandleTranslations;

    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function allProjects()
    {
        $locale = app()->getLocale();
        $news = $this->model->published()
            ->whereHas('translations', function ($query) use ($locale) {
                $query->where('locale', $locale)->where('active', true);
            })
            ->orderBy('position')
            ->latest();

        if ($search = request('search')) {
            $news->whereHas('translations', function ($query) use ($locale, $search) {
                $query->where('locale', $locale)
                    ->where('active', true)
                    ->where('title', 'like', '%'.$search.'%');
            });
        }

        return $news->get();
    }

    public function afterSave($object, $fields)
    {
        $fields = $this->useEnglishSlugForEveryLocale($object, $fields);
        $this->updateRepeater($object, $fields, 'technologies', 'ProjectTechnology', 'project-technology');
        parent::afterSave($object, $fields);

        $this->synchronizeLocaleSlugs($object);
    }

    private function useEnglishSlugForEveryLocale($object, array $fields): array
    {
        if (! isset($fields['slug']) || ! is_array($fields['slug'])) {
            return $fields;
        }

        $slug = $fields['slug']['en'] ?? $object->getSlug('en');
        if (! $slug) {
            return $fields;
        }

        foreach (getLocales() as $locale) {
            $fields['slug'][$locale] = $slug;
        }

        return $fields;
    }

    private function synchronizeLocaleSlugs($object): void
    {
        $englishSlug = $object->slugs()->where('locale', 'en')->orderByDesc('active')->orderByDesc('id')->value('slug');

        if (! $englishSlug) {
            return;
        }

        foreach (getLocales() as $locale) {
            $translation = $object->translate($locale);
            if (! $translation) {
                continue;
            }

            $object->disableLocaleSlugs($locale);
            $object->unsetRelation('slugs');
            $object->updateOrNewSlug([
                'slug' => $englishSlug,
                'locale' => $locale,
                'active' => (bool) $translation->active,
            ]);
        }
    }

    public function getFormFields($object)
    {
        $fields = parent::getFormFields($object);
        $fields = $this->getFormFieldsForRepeater($object, $fields, 'technologies', 'ProjectTechnology', 'project-technology');

        return $fields;
    }

//    public function featuredProject()
//    {
//        return $this->model
//            ->published()
//            ->orderBy('position')
//            ->where('featured', 1)
//            ->latest()
//            ->take(3)
//            ->get();
//    }
//
//    public function filteredHomeProject($news)
//    {
//        return $this->model
//            ->published()
//            ->orderBy('position')
//            ->whereNotIn('id', $news->pluck('id'))
//            ->latest()->take(4)
//            ->get();
//    }
//
//    public function threeLatest($news = null)
//    {
//        $newsId = $news ? $news->id : null;
//
//        return $this->model
//            ->where('id', '!=', $newsId)
//            ->orderBy('position')
//            ->published()
//            ->latest()
//            ->take(3)
//            ->get();
//    }
}
