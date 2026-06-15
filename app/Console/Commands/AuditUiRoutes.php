<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class AuditUiRoutes extends Command
{
    protected $signature = 'routes:audit-ui
        {--user=yazan : Username to log in as}
        {--password=1 : Password for the browser login}
        {--base-url= : Existing app URL to test. If empty, a temporary local server is started}
        {--headed : Show the browser}
        {--json=storage/app/route-load-audit/ui-requests-latest.json : Where to save the JSON report}';

    protected $description = 'UI requests audit for configured browser pages.';

    public function handle(): int
    {
        $user = User::where('username', $this->option('user'))->first();
        if (!$user) {
            $this->error('User not found: ' . $this->option('user'));
            return self::FAILURE;
        }

        if (!File::exists(base_path('node_modules/playwright'))) {
            $this->error('Playwright is not installed. Run npm install first.');
            return self::FAILURE;
        }

        $server = null;
        $baseUrl = rtrim((string) $this->option('base-url'), '/');

        if ($baseUrl === '') {
            [$server, $baseUrl] = $this->startServer();
        }

        try {
            $results = $this->runPlaywright($baseUrl);
        } finally {
            if ($server) {
                $server->stop(1);
            }
        }

        $this->writeReport($results, $baseUrl);
        $this->printTable($results);

        $failed = collect($results)->where('state', 'failed')->count();
        if ($failed > 0) {
            $this->error($failed . ' UI request audit(s) failed.');
            return self::FAILURE;
        }

        $this->info('No UI request audit failures found.');
        return self::SUCCESS;
    }

    private function startServer(): array
    {
        for ($port = 8899; $port <= 8910; $port++) {
            $baseUrl = 'http://127.0.0.1:' . $port;
            if ($this->canConnect($baseUrl . '/login')) {
                return [null, $baseUrl];
            }

            $process = new Process([PHP_BINARY, 'artisan', 'serve', '--host=127.0.0.1', '--port=' . $port], base_path());
            $process->setTimeout(null);
            $process->start();

            $deadline = microtime(true) + 10;
            while (microtime(true) < $deadline) {
                if ($this->canConnect($baseUrl . '/login')) {
                    return [$process, $baseUrl];
                }

                usleep(200000);
            }

            $process->stop(1);
        }

        throw new \RuntimeException('Could not start a local audit server.');
    }

    private function canConnect(string $url): bool
    {
        $context = stream_context_create([
            'http' => [
                'ignore_errors' => true,
                'timeout' => 2,
            ],
        ]);

        return @file_get_contents($url, false, $context) !== false;
    }

    private function runPlaywright(string $baseUrl): array
    {
        $process = new Process([
            'node',
            base_path('tools/ui-audit/run-ui-audit.mjs'),
        ], base_path(), [
            'UI_AUDIT_BASE_URL' => $baseUrl,
            'UI_AUDIT_USER' => $this->option('user'),
            'UI_AUDIT_PASSWORD' => $this->option('password'),
            'UI_AUDIT_HEADED' => $this->option('headed') ? '1' : '0',
            'UI_AUDIT_TARGETS' => json_encode(config('ui_audit.audits', [])),
        ], null, 120);

        $process->run();

        $payload = json_decode($process->getOutput(), true);
        if (!is_array($payload) || !isset($payload['results'])) {
            throw new \RuntimeException(trim($process->getErrorOutput()) ?: 'UI audit runner did not return JSON.');
        }

        return $payload['results'];
    }

    private function writeReport(array $results, string $baseUrl): void
    {
        $path = base_path($this->option('json'));
        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode([
            'created_at' => now()->toDateTimeString(),
            'user' => $this->option('user'),
            'base_url' => $baseUrl,
            'results' => $results,
        ], JSON_PRETTY_PRINT));
    }

    private function printTable(array $results): void
    {
        $this->table(
            ['State', 'ms', 'Audit', 'Viewport', 'Page', 'Checks', 'Error'],
            collect($results)->map(function ($row) {
                return [
                    $row['state'],
                    $row['ms'],
                    $row['audit'],
                    $row['viewport'],
                    $row['path'],
                    $row['checks'],
                    $row['error'],
                ];
            })->all()
        );
    }
}
