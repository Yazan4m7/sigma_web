<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunAuditShortcut extends Command
{
    protected $signature = 'audit
        {target : gets, posts, ui, or all}
        {--user=yazan : Username to run audits as}
        {--password=1 : Password for the UI audit browser login}
        {--base-url= : Existing app URL for the UI audit}
        {--headed : Show the browser for the UI audit}';

    protected $description = 'Short audit command wrapper.';

    public function handle(): int
    {
        $target = strtolower((string) $this->argument('target'));

        $targets = [
            'get' => ['gets'],
            'gets' => ['gets'],
            'post' => ['posts'],
            'posts' => ['posts'],
            'ui' => ['ui'],
            'all' => ['gets', 'posts', 'ui'],
        ];

        if (!isset($targets[$target])) {
            $this->error('Use one of: gets, posts, ui, all');
            return self::FAILURE;
        }

        $exitCode = self::SUCCESS;

        foreach ($targets[$target] as $audit) {
            $result = $this->runAudit($audit);
            if ($result !== self::SUCCESS) {
                $exitCode = $result;
            }
        }

        return $exitCode;
    }

    private function runAudit(string $audit): int
    {
        if ($audit === 'gets') {
            return $this->call('routes:audit-get-requests', [
                '--user' => $this->option('user'),
            ]);
        }

        if ($audit === 'posts') {
            return $this->call('routes:audit-post-requests', [
                '--user' => $this->option('user'),
            ]);
        }

        return $this->call('routes:audit-ui', [
            '--user' => $this->option('user'),
            '--password' => $this->option('password'),
            '--base-url' => $this->option('base-url'),
            '--headed' => (bool) $this->option('headed'),
        ]);
    }
}
