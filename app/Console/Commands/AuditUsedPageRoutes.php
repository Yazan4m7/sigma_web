<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Throwable;

class AuditUsedPageRoutes extends Command
{
    protected $signature = 'routes:audit-get-requests
        {--user=yazan : Username to load pages as}
        {--json=storage/app/route-load-audit/get-requests-latest.json : Where to save the JSON report}';

    protected $description = 'GET requests audit for safe app menu pages.';

    public function handle(): int
    {
        $user = User::where('username', $this->option('user'))->first();
        if (!$user) {
            $this->error('User not found: ' . $this->option('user'));
            return self::FAILURE;
        }

        $routes = config('route_audit.used_page_routes', []);
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $results = [];

        foreach ($routes as $name) {
            if (!Route::has($name)) {
                $results[] = $this->result($name, null, null, 'missing', 'Route name not found.');
                continue;
            }

            $uri = route($name, [], false);
            $request = Request::create($uri, 'GET');
            $start = microtime(true);

            try {
                Auth::onceUsingId($user->id);
                $response = $kernel->handle($request);
                $kernel->terminate($request, $response);

                $status = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null;
                $state = $status >= 500 ? 'failed' : 'ok';
                $results[] = $this->result($name, $uri, $status, $state, null, $start);
            } catch (Throwable $e) {
                $results[] = $this->result($name, $uri, 500, 'failed', get_class($e) . ': ' . $e->getMessage(), $start);
            } finally {
                Auth::logout();
            }
        }

        $this->writeReport($results);
        $this->printTable($results);

        $failed = collect($results)->where('state', 'failed')->count();
        $missing = collect($results)->where('state', 'missing')->count();

        if ($failed > 0) {
            $this->error($failed . ' GET request audit(s) failed.');
            return self::FAILURE;
        }

        if ($missing > 0) {
            $this->warn($missing . ' configured route(s) are missing.');
        }

        $this->info('No GET request audit failures found.');
        return self::SUCCESS;
    }

    private function result(string $name, ?string $uri, ?int $status, string $state, ?string $error = null, ?float $start = null): array
    {
        return [
            'route' => $name,
            'uri' => $uri,
            'status' => $status,
            'state' => $state,
            'ms' => $start ? (int) round((microtime(true) - $start) * 1000) : null,
            'error' => $error,
        ];
    }

    private function writeReport(array $results): void
    {
        $path = base_path($this->option('json'));
        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode([
            'created_at' => now()->toDateTimeString(),
            'user' => $this->option('user'),
            'results' => $results,
        ], JSON_PRETTY_PRINT));
    }

    private function printTable(array $results): void
    {
        $this->table(
            ['State', 'Status', 'ms', 'Route', 'URI', 'Error'],
            collect($results)->map(function ($row) {
                return [
                    $row['state'],
                    $row['status'],
                    $row['ms'],
                    $row['route'],
                    $row['uri'],
                    $row['error'],
                ];
            })->all()
        );
    }
}
