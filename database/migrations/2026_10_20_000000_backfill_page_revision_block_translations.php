<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $translatedBlockFields = [
        'paragraph' => ['title', 'paragraph'],
        'article-paragraph-h3' => ['title', 'paragraph'],
        'double-paragraph' => ['title', 'paragraph1', 'paragraph2'],
        'article-title' => ['title', 'subtitle'],
        'article-introduction' => ['title', 'paragraph'],
        'file-download-button' => ['title'],
        'latest-cv-download-button' => ['title'],
    ];

    public function up(): void
    {
        $this->transformRevisionBlockContent(true);
    }

    public function down(): void
    {
        $this->transformRevisionBlockContent(false);
    }

    private function transformRevisionBlockContent(bool $wrapEnglish): void
    {
        $table = 'page_revisions';

        if (! Schema::hasTable($table)) {
            return;
        }

        DB::table($table)->orderBy('id')->chunkById(100, function ($revisions) use ($table, $wrapEnglish) {
            foreach ($revisions as $revision) {
                $payload = json_decode($revision->payload, true);

                if (! is_array($payload) || ! isset($payload['blocks']) || ! is_array($payload['blocks'])) {
                    continue;
                }

                $changed = false;
                $payload['blocks'] = $this->transformBlockTree($payload['blocks'], $wrapEnglish, $changed);

                if ($changed) {
                    DB::table($table)->where('id', $revision->id)->update([
                        'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ]);
                }
            }
        });
    }

    private function transformBlockTree(array $nodes, bool $wrapEnglish, bool &$changed): array
    {
        if (isset($nodes['type'], $nodes['content']) && is_string($nodes['type']) && is_array($nodes['content'])) {
            $type = preg_replace('/^a17-block-/', '', $nodes['type']);

            foreach ($this->translatedBlockFields[$type] ?? [] as $field) {
                if (! array_key_exists($field, $nodes['content'])) {
                    continue;
                }

                if ($wrapEnglish && is_string($nodes['content'][$field])) {
                    $nodes['content'][$field] = ['en' => $nodes['content'][$field]];
                    $changed = true;
                } elseif (! $wrapEnglish
                    && is_array($nodes['content'][$field])
                    && array_keys($nodes['content'][$field]) === ['en']) {
                    $nodes['content'][$field] = $nodes['content'][$field]['en'];
                    $changed = true;
                }
            }
        }

        foreach ($nodes as $key => $value) {
            if (is_array($value)) {
                $nodes[$key] = $this->transformBlockTree($value, $wrapEnglish, $changed);
            }
        }

        return $nodes;
    }
};
