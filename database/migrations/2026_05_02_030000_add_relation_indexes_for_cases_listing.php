<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfMissing('notes', 'idx_notes_case_id', ['case_id']);
        $this->addIndexIfMissing('notes', 'idx_notes_written_by', ['written_by']);
        $this->addIndexIfMissing('notes', 'idx_notes_deleted_at', ['deleted_at']);

        $this->addIndexIfMissing('case_tags', 'idx_case_tags_case_id', ['case_id']);
        $this->addIndexIfMissing('case_tags', 'idx_case_tags_tag_id', ['tag_id']);
        $this->addIndexIfMissing('case_tags', 'idx_case_tags_deleted_at', ['deleted_at']);

        $this->addIndexIfMissing('files', 'idx_files_case_id', ['case_id']);
        $this->addIndexIfMissing('files', 'idx_files_deleted_at', ['deleted_at']);
    }

    public function down(): void
    {
        $this->dropIndexIfExists('notes', 'idx_notes_case_id');
        $this->dropIndexIfExists('notes', 'idx_notes_written_by');
        $this->dropIndexIfExists('notes', 'idx_notes_deleted_at');

        $this->dropIndexIfExists('case_tags', 'idx_case_tags_case_id');
        $this->dropIndexIfExists('case_tags', 'idx_case_tags_tag_id');
        $this->dropIndexIfExists('case_tags', 'idx_case_tags_deleted_at');

        $this->dropIndexIfExists('files', 'idx_files_case_id');
        $this->dropIndexIfExists('files', 'idx_files_deleted_at');
    }

    private function addIndexIfMissing(string $table, string $indexName, array $columns): void
    {
        if ($this->indexExists($table, $indexName)) {
            return;
        }

        $wrappedColumns = implode(', ', array_map(static function (string $column): string {
            return sprintf('`%s`', $column);
        }, $columns));

        DB::statement(sprintf(
            'ALTER TABLE `%s` ADD INDEX `%s` (%s)',
            $table,
            $indexName,
            $wrappedColumns
        ));
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if (!$this->indexExists($table, $indexName)) {
            return;
        }

        DB::statement(sprintf(
            'ALTER TABLE `%s` DROP INDEX `%s`',
            $table,
            $indexName
        ));
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select(sprintf('SHOW INDEX FROM `%s`', $table));

        foreach ($indexes as $index) {
            if (($index->Key_name ?? null) === $indexName) {
                return true;
            }
        }

        return false;
    }
};
