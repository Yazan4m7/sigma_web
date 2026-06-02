<?php

namespace App\Http\Controllers;

class DeployController extends Controller
{
    private string $projectPath = '/var/www/sigma';

    public function rollback()
    {
        $output = $this->runCommand('git checkout HEAD~1 -- . 2>&1');
        $this->refreshApplicationCaches();

        return back()->with('deploy_status', 'Rolled back successfully.' . ($output !== '' ? ' ' . $output : ''));
    }

    public function deploy()
    {
        $output = $this->runCommand('git pull origin main 2>&1');
        $this->refreshApplicationCaches();

        return back()->with('deploy_status', 'Deployed: ' . $output);
    }

    private function refreshApplicationCaches(): void
    {
        $this->runCommand('php artisan config:cache 2>&1');
        $this->runCommand('php artisan view:clear 2>&1');
    }

    private function runCommand(string $command): string
    {
        $output = shell_exec('cd ' . escapeshellarg($this->projectPath) . ' && ' . $command);

        return trim((string) $output);
    }
}
