<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleBlocks;
use A17\Twill\Repositories\Behaviors\HandleFiles;
use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleSlugs;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Page;

class PageRepository extends ModuleRepository
{
    use HandleBlocks, HandleSlugs, HandleMedias, HandleRevisions, HandleFiles, HandleTranslations;

    public function __construct(Page $model)
    {
        $this->model = $model;
    }

    public function afterSave($object, $fields)
    {
        $fields = $this->useEnglishSlugForEveryLocale($object, $fields);

        parent::afterSave($object, $fields);

        $this->synchronizeLocaleSlugs($object);
    }

    public function getFormFields($object)
    {
        $fields = parent::getFormFields($object);

        return $this->hydrateSharedSlugFormFields($object, $fields);
    }

    private function hydrateSharedSlugFormFields($object, array $fields): array
    {
        $englishSlug = $object->slugs()
            ->where('locale', 'en')
            ->orderByDesc('active')
            ->orderByDesc('id')
            ->value('slug');

        if ($englishSlug === null || $englishSlug === '') {
            return $fields;
        }

        foreach (getLocales() as $locale) {
            $fields['translations']['slug'][$locale] = $englishSlug;
        }

        return $fields;
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
}
