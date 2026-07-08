<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WipeSessions extends Command
{
    protected $signature = 'sessions:wipe
        {--force : Run without confirmation}
        {--dry-run : Count sessions without deleting them}';

    protected $description = 'Clear all stored sessions so every user is logged out.';

    public function handle(): int
    {
        $driver = config('session.driver');
        $dryRun = (bool) $this->option('dry-run');

        if (! $dryRun && ! $this->option('force') && ! $this->confirm('This will log out all users. Continue?')) {
            $this->info('Canceled.');

            return self::SUCCESS;
        }

        if ($driver === 'file') {
            return $this->wipeFileSessions($dryRun);
        }

        if ($driver === 'database') {
            return $this->wipeDatabaseSessions($dryRun);
        }

        $this->error("The '{$driver}' session driver is not supported by this safe wipe command.");
        $this->warn('Use the file or database session driver, or add a driver-specific implementation before scheduling this.');

        return self::FAILURE;
    }

    private function wipeFileSessions(bool $dryRun): int
    {
        $path = config('session.files');

        if (! is_string($path) || ! File::isDirectory($path)) {
            $this->error('Session directory does not exist.');

            return self::FAILURE;
        }

        $deleted = 0;

        foreach (File::files($path) as $file) {
            if (str_starts_with($file->getFilename(), '.')) {
                continue;
            }

            $deleted++;

            if (! $dryRun) {
                File::delete($file->getPathname());
            }
        }

        $action = $dryRun ? 'would be cleared' : 'cleared';
        $this->info("{$deleted} file sessions {$action}.");

        return self::SUCCESS;
    }

    private function wipeDatabaseSessions(bool $dryRun): int
    {
        $connection = config('session.connection');
        $table = config('session.table', 'sessions');
        $query = DB::connection($connection)->table($table);
        $count = $query->count();

        if (! $dryRun) {
            $query->delete();
        }

        $action = $dryRun ? 'would be cleared' : 'cleared';
        $this->info("{$count} database sessions {$action}.");

        return self::SUCCESS;
    }
}
