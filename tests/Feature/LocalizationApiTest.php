<?php

namespace Tests\Feature;

use A17\Twill\Models\Translations\SettingTranslation;
use App\Models\Cache;
use App\Models\Page;
use App\Models\Project;
use App\Repositories\PageRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LocalizationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_english_and_german_page_content_use_the_same_response_shape(): void
    {
        $homeId = $this->createPage('home', 'Home', 'Startseite', true, [
            'title' => ['en' => 'Welcome', 'de' => 'Willkommen'],
            'paragraph' => ['en' => '<p>English home</p>', 'de' => '<p>Deutsche Startseite</p>'],
        ]);
        $aboutId = $this->createPage('about', 'About', 'Über mich', true, [
            'title' => ['en' => 'About heading', 'de' => 'Über mich'],
            'paragraph' => ['en' => '<p>English about</p>', 'de' => '<p>Deutscher Inhalt</p>'],
        ]);

        $english = $this->apiGet('/home');
        $english->assertOk()
            ->assertJsonPath('data.title', 'Home')
            ->assertJsonStructure(['data' => ['title', 'content']]);
        $this->assertSame(['title', 'content'], array_keys($english->json('data')));
        $this->assertStringContainsString('English home', $english->json('data.content'));

        $german = $this->apiGet('/home?locale=de');
        $german->assertOk()->assertJsonPath('data.title', 'Startseite');
        $this->assertStringContainsString('Willkommen', $german->json('data.content'));
        $this->assertStringContainsString('Deutsche Startseite', $german->json('data.content'));

        $this->apiGet('/about?locale=de')->assertOk()->assertJsonPath('data.title', 'Über mich');
        $this->apiGet('/about')->assertOk()->assertJsonPath('data.title', 'About');
        $this->assertSame('about', DB::table('page_slugs')->where('page_id', $aboutId)->where('locale', 'en')->value('slug'));
        $this->assertSame('home', DB::table('page_slugs')->where('page_id', $homeId)->where('locale', 'de')->value('slug'));
    }

    public function test_missing_german_fields_fall_back_to_english_but_unpublished_german_pages_are_not_available(): void
    {
        $this->createPage('about', 'About', null, true, [
            'title' => ['en' => 'About heading'],
            'paragraph' => ['en' => '<p>English fallback text</p>'],
        ]);
        $this->createPage('contact', 'Contact', 'Kontakt', false, null);

        $response = $this->apiGet('/about?locale=de');
        $response->assertOk()->assertJsonPath('data.title', 'About');
        $this->assertStringContainsString('English fallback text', $response->json('data.content'));

        $this->apiGet('/contact?locale=de')->assertNotFound();
    }

    public function test_project_list_and_detail_localize_text_and_preserve_slugs_and_response_fields(): void
    {
        $publishedProjectId = $this->createProject('neutral-project', 'English title', 'English description', true, 'Deutscher Titel', 'Deutsche Beschreibung');
        $this->createProject('english-only-project', 'English only', 'English fallback', true, null, null);

        $germanList = $this->apiGet('/projects?locale=de');
        $germanList->assertOk()->assertJsonPath('data.0.slug', 'neutral-project')
            ->assertJsonPath('data.0.title', 'Deutscher Titel');
        $this->assertCount(1, $germanList->json('data'));
        $this->assertSame(
            ['slug', 'technologies', 'title', 'image', 'image_mobile', 'image_lqpi', 'image_lqpi_mobile', 'aspect_ratio'],
            array_keys($germanList->json('data.0'))
        );

        $englishList = $this->apiGet('/projects');
        $englishList->assertOk();
        $this->assertCount(2, $englishList->json('data'));
        $this->assertContains('English title', array_column($englishList->json('data'), 'title'));
        $this->assertSame(
            ['slug', 'technologies', 'title', 'image', 'image_mobile', 'image_lqpi', 'image_lqpi_mobile', 'aspect_ratio'],
            array_keys($englishList->json('data.0'))
        );

        $englishDetail = $this->apiGet('/projects/neutral-project');
        $englishDetail->assertOk()
            ->assertJsonPath('data.title', 'English title')
            ->assertJsonPath('data.description', 'English description');
        $this->assertSame(['title', 'image', 'description', 'content'], array_keys($englishDetail->json('data')));

        $detail = $this->apiGet('/projects/neutral-project?locale=de');
        $detail->assertOk()
            ->assertJsonPath('data.title', 'Deutscher Titel')
            ->assertJsonPath('data.description', 'Deutsche Beschreibung')
            ->assertJsonStructure(['data' => ['title', 'image', 'description', 'content']]);
        $this->assertSame(['title', 'image', 'description', 'content'], array_keys($detail->json('data')));

        $fallback = $this->apiGet('/projects/english-only-project?locale=de');
        $fallback->assertNotFound();

        $this->assertSame($publishedProjectId, Project::whereHas('slugs', function ($query) {
            $query->where('slug', 'neutral-project')->where('locale', 'en');
        })->value('id'));
        $this->assertSame('neutral-project', DB::table('project_slugs')->where('project_id', $publishedProjectId)->where('locale', 'de')->value('slug'));
    }

    public function test_project_detail_falls_back_for_missing_german_fields_and_renders_localized_blocks(): void
    {
        $projectId = $this->createProject('portfolio-piece', 'English project', 'English description', true, null, null, [
            'title' => ['en' => 'English block title', 'de' => 'Deutscher Blocktitel'],
            'paragraph' => ['en' => '<p>English block</p>', 'de' => '<p>Deutscher Block</p>'],
        ]);
        DB::table('project_translations')->where('project_id', $projectId)->where('locale', 'de')->update([
            'title' => 'Deutscher Projekttitel',
            'description' => null,
            'active' => true,
        ]);

        $response = $this->apiGet('/projects/portfolio-piece?locale=de');
        $response->assertOk()
            ->assertJsonPath('data.title', 'Deutscher Projekttitel')
            ->assertJsonPath('data.description', 'English description');
        $this->assertStringContainsString('Deutscher Blocktitel', $response->json('data.content'));
        $this->assertStringContainsString('Deutscher Block', $response->json('data.content'));
    }

    public function test_settings_are_locale_filtered_fall_back_deterministically_and_invalidate_shared_cache(): void
    {
        $globalTitle = $this->createSetting('global_title', ['en' => 'Armin Portfolio', 'de' => 'Armin Portfolio DE']);
        $metaTitle = $this->createSetting('projects_meta_title', ['en' => 'Projects', 'de' => 'Projekte']);
        $this->createSetting('home_meta_description', ['en' => 'English description']);
        $cache = Cache::create(['cache' => 'before']);

        $german = $this->apiGet('/settings?locale=de');
        $german->assertOk()
            ->assertJsonPath('projects_meta_title', 'Projekte | Armin Portfolio DE')
            ->assertJsonPath('home_meta_description', 'English description');

        $english = $this->apiGet('/settings');
        $english->assertOk()->assertJsonPath('projects_meta_title', 'Projects | Armin Portfolio');
        $this->assertSame([
            'favicon', 'social_github', 'social_linkedin', 'social_discord', 'social_email',
            'home_meta_title', 'home_meta_description', 'home_og', 'projects_meta_title',
            'projects_meta_description', 'projects_og', 'about_meta_title', 'about_meta_description',
            'about_og', 'contact_meta_title', 'contact_meta_description', 'contact_og', 'cv',
        ], array_keys($english->json()));

        SettingTranslation::where('setting_id', $metaTitle)->where('locale', 'de')->firstOrFail()->update([
            'value' => 'Portfolio',
        ]);
        $this->assertNotSame('before', $cache->fresh()->cache);
        $this->apiGet('/settings')->assertJsonPath('projects_meta_title', 'Projects | Armin Portfolio');
        $this->apiGet('/settings?locale=de')->assertJsonPath('projects_meta_title', 'Portfolio | Armin Portfolio DE');

        $this->assertSame($globalTitle, DB::table('settings')->where('key', 'global_title')->value('id'));
    }

    public function test_unsupported_locale_returns_a_consistent_bad_request(): void
    {
        $this->apiGet('/settings?locale=fr')->assertStatus(400)->assertExactJson([
            'message' => 'Unsupported locale.',
            'errors' => [
                'locale' => ['The selected locale is invalid.'],
            ],
        ]);
    }

    public function test_migration_backfills_existing_english_rows_without_removing_legacy_content(): void
    {
        $now = now();
        $pageId = DB::table('pages')->insertGetId([
            'title' => 'Legacy About',
            'published' => true,
            'position' => 7,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $projectId = DB::table('projects')->insertGetId([
            'title' => 'Legacy Project',
            'description' => 'Legacy project description',
            'published' => true,
            'position' => 3,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('blocks')->insert([
            'blockable_id' => $pageId,
            'blockable_type' => Page::class,
            'position' => 1,
            'content' => json_encode(['title' => 'Legacy heading', 'paragraph' => '<p>Legacy block</p>']),
            'type' => 'paragraph',
            'child_key' => null,
            'parent_id' => null,
            'editor_name' => 'default',
        ]);

        $migration = require database_path('migrations/2026_10_19_000000_add_page_and_project_translations.php');
        $migration->backfillLegacyEnglishContent();

        $this->assertSame('Legacy About', DB::table('pages')->where('id', $pageId)->value('title'));
        $this->assertSame('Legacy About', DB::table('page_translations')->where('page_id', $pageId)->where('locale', 'en')->value('title'));
        $this->assertTrue((bool) DB::table('page_translations')->where('page_id', $pageId)->where('locale', 'en')->value('active'));
        $this->assertSame('Legacy Project', DB::table('project_translations')->where('project_id', $projectId)->where('locale', 'en')->value('title'));
        $this->assertSame('Legacy project description', DB::table('project_translations')->where('project_id', $projectId)->where('locale', 'en')->value('description'));
        $this->assertSame(7, DB::table('pages')->where('id', $pageId)->value('position'));
        $content = json_decode(DB::table('blocks')->where('blockable_id', $pageId)->value('content'), true);
        $this->assertSame('Legacy heading', $content['title']['en']);
        $this->assertSame('<p>Legacy block</p>', $content['paragraph']['en']);
    }

    public function test_twill_repositories_persist_and_return_page_and_project_translations(): void
    {
        $languages = [
            ['value' => 'en', 'published' => true],
            ['value' => 'de', 'published' => true],
        ];

        $page = app(PageRepository::class)->create([
            'title' => ['en' => 'CMS English page', 'de' => 'CMS deutsche Seite'],
            'slug' => ['en' => 'cms-page', 'de' => 'cms-page'],
            'published' => true,
            'position' => 1,
            'languages' => $languages,
        ]);
        $this->assertSame('CMS English page', $page->fresh()->translate('en')->title);
        $this->assertSame('CMS deutsche Seite', $page->fresh()->translate('de')->title);
        $this->assertTrue($page->fresh()->hasActiveTranslation('de'));
        $pageFields = app(PageRepository::class)->getFormFields($page->fresh());
        $this->assertSame('CMS English page', $pageFields['translations']['title']['en']);
        $this->assertSame('CMS deutsche Seite', $pageFields['translations']['title']['de']);

        $project = app(ProjectRepository::class)->create([
            'title' => ['en' => 'CMS English project', 'de' => 'CMS deutsches Projekt'],
            'description' => ['en' => 'English description', 'de' => 'Deutsche Beschreibung'],
            'slug' => ['en' => 'cms-project', 'de' => 'cms-project'],
            'published' => true,
            'position' => 1,
            'languages' => $languages,
        ]);
        $this->assertSame('CMS English project', $project->fresh()->translate('en')->title);
        $this->assertSame('CMS deutsches Projekt', $project->fresh()->translate('de')->title);
        $this->assertSame('Deutsche Beschreibung', $project->fresh()->translate('de')->description);
        $this->assertTrue($project->fresh()->hasActiveTranslation('de'));

        $draftPage = app(PageRepository::class)->create([
            'title' => ['en' => 'English only page', 'de' => ''],
            'slug' => ['en' => 'english-only-page', 'de' => 'english-only-page'],
            'published' => true,
            'position' => 2,
            'languages' => [
                ['value' => 'en', 'published' => true],
                ['value' => 'de', 'published' => false],
            ],
        ]);
        $this->assertTrue($draftPage->fresh()->hasActiveTranslation('en'));
        $this->assertFalse($draftPage->fresh()->hasActiveTranslation('de'));
    }

    private function createPage(string $slug, string $englishTitle, ?string $germanTitle, bool $germanActive, ?array $blockContent): int
    {
        $now = now();
        $id = DB::table('pages')->insertGetId([
            'title' => $englishTitle,
            'published' => true,
            'position' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->insertTranslation('page_translations', 'page_id', $id, 'en', [
            'title' => $englishTitle,
            'active' => true,
        ]);
        $this->insertTranslation('page_translations', 'page_id', $id, 'de', [
            'title' => $germanTitle,
            'active' => $germanActive,
        ]);
        $this->insertSlug('page_slugs', 'page_id', $id, $slug, 'en', true);
        $this->insertSlug('page_slugs', 'page_id', $id, $slug, 'de', $germanActive);

        if ($blockContent) {
            DB::table('blocks')->insert([
                'blockable_id' => $id,
                'blockable_type' => Page::class,
                'position' => 1,
                'content' => json_encode($blockContent),
                'type' => 'paragraph',
                'child_key' => null,
                'parent_id' => null,
                'editor_name' => 'default',
            ]);
        }

        return $id;
    }

    private function apiGet(string $path)
    {
        $route = collect(app('router')->getRoutes()->getRoutes())->first(function (Route $route) {
            return $route->uri() === 'settings';
        });
        $host = $route && $route->getDomain() ? $route->getDomain() : 'localhost';

        return $this->getJson('http://'.$host.$path);
    }

    private function createProject(string $slug, string $englishTitle, string $englishDescription, bool $published, ?string $germanTitle, ?string $germanDescription, ?array $blockContent = null): int
    {
        $now = now();
        $id = DB::table('projects')->insertGetId([
            'title' => $englishTitle,
            'description' => $englishDescription,
            'published' => $published,
            'position' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->insertTranslation('project_translations', 'project_id', $id, 'en', [
            'title' => $englishTitle,
            'description' => $englishDescription,
            'active' => true,
        ]);
        $this->insertTranslation('project_translations', 'project_id', $id, 'de', [
            'title' => $germanTitle,
            'description' => $germanDescription,
            'active' => $germanTitle !== null,
        ]);
        $this->insertSlug('project_slugs', 'project_id', $id, $slug, 'en', true);
        $this->insertSlug('project_slugs', 'project_id', $id, $slug, 'de', $germanTitle !== null);

        $mediaId = DB::table('medias')->insertGetId([
            'uuid' => 'local/project-'.$id.'.jpg',
            'alt_text' => 'Project image',
            'width' => 910,
            'height' => 512,
            'caption' => null,
            'filename' => 'project-'.$id.'.jpg',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('mediables')->insert([
            'mediable_id' => $id,
            'mediable_type' => Project::class,
            'media_id' => $mediaId,
            'crop_x' => 0,
            'crop_y' => 0,
            'crop_w' => 910,
            'crop_h' => 512,
            'role' => 'image',
            'crop' => 'default',
            'lqip_data' => null,
            'ratio' => '16/9',
            'metadatas' => '{"altText":null,"caption":null,"video":null}',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('mediables')->insert([
            'mediable_id' => $id,
            'mediable_type' => Project::class,
            'media_id' => $mediaId,
            'crop_x' => 0,
            'crop_y' => 0,
            'crop_w' => 910,
            'crop_h' => 512,
            'role' => 'image',
            'crop' => 'og',
            'lqip_data' => null,
            'ratio' => '16/9',
            'metadatas' => '{"altText":null,"caption":null,"video":null}',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        if ($blockContent) {
            DB::table('blocks')->insert([
                'blockable_id' => $id,
                'blockable_type' => Project::class,
                'position' => 1,
                'content' => json_encode($blockContent),
                'type' => 'paragraph',
                'child_key' => null,
                'parent_id' => null,
                'editor_name' => 'default',
            ]);
        }

        return $id;
    }

    private function insertTranslation(string $table, string $foreignKey, int $id, string $locale, array $fields): void
    {
        $now = now();
        DB::table($table)->insert($fields + [
            $foreignKey => $id,
            'locale' => $locale,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function insertSlug(string $table, string $foreignKey, int $id, string $slug, string $locale, bool $active): void
    {
        $now = now();
        DB::table($table)->insert([
            $foreignKey => $id,
            'slug' => $slug,
            'locale' => $locale,
            'active' => $active,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function createSetting(string $key, array $values): int
    {
        $now = now();
        $id = DB::table('settings')->insertGetId([
            'key' => $key,
            'section' => 'settings',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach ($values as $locale => $value) {
            DB::table('setting_translations')->insert([
                'setting_id' => $id,
                'locale' => $locale,
                'value' => $value,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $id;
    }
}
