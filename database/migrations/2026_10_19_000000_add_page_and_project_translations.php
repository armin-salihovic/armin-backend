<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('page_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'page');
            $table->string('title', 200)->nullable();
        });

        Schema::create('project_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'project');
            $table->string('title', 200)->nullable();
            $table->text('description')->nullable();
        });

        $this->backfillLegacyEnglishContent();
    }

    public function down()
    {
        foreach (DB::table('page_translations')->where('locale', 'en')->get() as $translation) {
            DB::table('pages')->where('id', $translation->page_id)->update([
                'title' => $translation->title,
            ]);
        }

        foreach (DB::table('project_translations')->where('locale', 'en')->get() as $translation) {
            DB::table('projects')->where('id', $translation->project_id)->update([
                'title' => $translation->title,
                'description' => $translation->description,
            ]);
        }

        $this->unwrapEnglishBlockContent();

        Schema::dropIfExists('project_translations');
        Schema::dropIfExists('page_translations');
    }

    /**
     * Copy the existing English columns into Twill's native translation tables
     * while leaving the original columns in place for compatibility and rollback.
     */
    public function backfillLegacyEnglishContent(): void
    {
        foreach (DB::table('pages')->get(['id', 'title', 'published', 'created_at', 'updated_at']) as $page) {
            DB::table('page_translations')->updateOrInsert(
                ['page_id' => $page->id, 'locale' => 'en'],
                [
                    'title' => $page->title,
                    'active' => (bool) $page->published,
                    'created_at' => $page->created_at,
                    'updated_at' => $page->updated_at,
                ]
            );
        }

        foreach (DB::table('projects')->get(['id', 'title', 'description', 'published', 'created_at', 'updated_at']) as $project) {
            DB::table('project_translations')->updateOrInsert(
                ['project_id' => $project->id, 'locale' => 'en'],
                [
                    'title' => $project->title,
                    'description' => $project->description,
                    'active' => (bool) $project->published,
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at,
                ]
            );
        }

        $this->wrapLegacyBlockContent();
    }

    private function wrapLegacyBlockContent(): void
    {
        $translatedFields = [
            'paragraph' => ['title', 'paragraph'],
            'article-paragraph-h3' => ['title', 'paragraph'],
            'double-paragraph' => ['title', 'paragraph1', 'paragraph2'],
            'article-title' => ['title', 'subtitle'],
            'article-introduction' => ['title', 'paragraph'],
            'file-download-button' => ['title'],
            'latest-cv-download-button' => ['title'],
        ];
        $blocksTable = config('twill.blocks_table', 'blocks');

        if (! Schema::hasTable($blocksTable)) {
            return;
        }

        foreach (DB::table($blocksTable)->get(['id', 'type', 'content']) as $block) {
            $fields = $translatedFields[$block->type] ?? [];
            $content = json_decode($block->content, true);

            if (! $fields || ! is_array($content)) {
                continue;
            }

            $changed = false;
            foreach ($fields as $field) {
                if (isset($content[$field]) && is_string($content[$field])) {
                    $content[$field] = ['en' => $content[$field]];
                    $changed = true;
                }
            }

            if ($changed) {
                DB::table($blocksTable)->where('id', $block->id)->update([
                    'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]);
            }
        }
    }

    private function unwrapEnglishBlockContent(): void
    {
        $translatedFields = [
            'paragraph' => ['title', 'paragraph'],
            'article-paragraph-h3' => ['title', 'paragraph'],
            'double-paragraph' => ['title', 'paragraph1', 'paragraph2'],
            'article-title' => ['title', 'subtitle'],
            'article-introduction' => ['title', 'paragraph'],
            'file-download-button' => ['title'],
            'latest-cv-download-button' => ['title'],
        ];
        $blocksTable = config('twill.blocks_table', 'blocks');

        if (! Schema::hasTable($blocksTable)) {
            return;
        }

        foreach (DB::table($blocksTable)->get(['id', 'type', 'content']) as $block) {
            $fields = $translatedFields[$block->type] ?? [];
            $content = json_decode($block->content, true);

            if (! $fields || ! is_array($content)) {
                continue;
            }

            $changed = false;
            foreach ($fields as $field) {
                if (isset($content[$field]) && is_array($content[$field]) && array_key_exists('en', $content[$field])) {
                    $content[$field] = $content[$field]['en'];
                    $changed = true;
                }
            }

            if ($changed) {
                DB::table($blocksTable)->where('id', $block->id)->update([
                    'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]);
            }
        }
    }
};
