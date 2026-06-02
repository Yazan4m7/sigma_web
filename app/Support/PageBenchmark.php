<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageBenchmark
{
    private const ATTRIBUTE_KEY = '_sigma_page_benchmark';
    private const SUMMARY_KEY = '_sigma_page_benchmark_summary';

    public static function shouldProfile(?Request $request = null): bool
    {
        $request = $request ?: request();

        if (!$request instanceof Request) {
            return false;
        }

        $flag = $request->query('__benchmark', $request->header('X-Sigma-Benchmark'));

        if (is_bool($flag)) {
            return $flag;
        }

        if ($flag === null) {
            return false;
        }

        $normalized = filter_var($flag, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        return $normalized ?? trim((string) $flag) !== '';
    }

    public static function bootIfActive(Request $request, ?string $profile = null): bool
    {
        if (!self::shouldProfile($request)) {
            return false;
        }

        if ($request->attributes->has(self::ATTRIBUTE_KEY)) {
            return true;
        }

        DB::flushQueryLog();
        DB::enableQueryLog();

        $startedAt = microtime(true);

        $request->attributes->set(self::ATTRIBUTE_KEY, [
            'profile' => $profile ?: optional($request->route())->getName() ?: trim($request->path(), '/'),
            'started_at' => $startedAt,
            'last_mark_at' => $startedAt,
            'marks' => [],
            'finished' => false,
        ]);

        self::mark($request, 'benchmark.boot');

        return true;
    }

    public static function mark(Request $request, string $label, array $context = []): void
    {
        if (!$request->attributes->has(self::ATTRIBUTE_KEY)) {
            return;
        }

        $payload = $request->attributes->get(self::ATTRIBUTE_KEY);
        $now = microtime(true);
        $querySummary = self::querySummary();

        $payload['marks'][] = array_merge([
            'label' => $label,
            'elapsed_ms' => round(($now - $payload['started_at']) * 1000, 2),
            'delta_ms' => round(($now - $payload['last_mark_at']) * 1000, 2),
            'query_count' => $querySummary['query_count'],
            'sql_ms' => $querySummary['sql_ms'],
            'memory_mb' => round(memory_get_usage(true) / 1048576, 2),
        ], $context);

        $payload['last_mark_at'] = $now;

        $request->attributes->set(self::ATTRIBUTE_KEY, $payload);
    }

    public static function finish(Request $request, $response = null): ?array
    {
        if (!$request->attributes->has(self::ATTRIBUTE_KEY)) {
            return null;
        }

        $payload = $request->attributes->get(self::ATTRIBUTE_KEY);

        if (!empty($payload['finished'])) {
            return $request->attributes->get(self::SUMMARY_KEY);
        }

        self::mark($request, 'request.complete', [
            'status' => self::responseStatus($response),
            'response_bytes' => self::responseBytes($response),
        ]);

        $payload = $request->attributes->get(self::ATTRIBUTE_KEY);
        $marks = $payload['marks'];
        $lastMark = end($marks) ?: null;
        $summary = [
            'profile' => $payload['profile'],
            'route' => optional($request->route())->getName(),
            'path' => '/' . ltrim($request->path(), '/'),
            'method' => $request->method(),
            'user_id' => optional($request->user())->id,
            'status' => self::responseStatus($response),
            'duration_ms' => $lastMark['elapsed_ms'] ?? round((microtime(true) - $payload['started_at']) * 1000, 2),
            'query_count' => $lastMark['query_count'] ?? self::querySummary()['query_count'],
            'sql_ms' => $lastMark['sql_ms'] ?? self::querySummary()['sql_ms'],
            'response_bytes' => self::responseBytes($response),
            'marks' => $marks,
        ];

        $payload['finished'] = true;
        $request->attributes->set(self::ATTRIBUTE_KEY, $payload);
        $request->attributes->set(self::SUMMARY_KEY, $summary);

        Log::info('[page-benchmark] Request summary', $summary);

        return $summary;
    }

    public static function summary(Request $request): ?array
    {
        return $request->attributes->get(self::SUMMARY_KEY);
    }

    public static function serverTimingHeader(array $summary): string
    {
        $metrics = [
            sprintf('app;dur=%.2f', $summary['duration_ms'] ?? 0),
            sprintf('sql;dur=%.2f', $summary['sql_ms'] ?? 0),
        ];

        foreach (array_slice($summary['marks'] ?? [], 0, 8) as $mark) {
            $label = preg_replace('/[^a-z0-9]+/i', '_', strtolower($mark['label'] ?? 'mark'));
            $metrics[] = sprintf('%s;dur=%.2f', trim($label, '_'), $mark['delta_ms'] ?? 0);
        }

        return implode(', ', array_filter($metrics));
    }

    private static function querySummary(): array
    {
        $queryLog = DB::getQueryLog();

        return [
            'query_count' => count($queryLog),
            'sql_ms' => round(array_sum(array_column($queryLog, 'time')), 2),
        ];
    }

    private static function responseStatus($response): ?int
    {
        return is_object($response) && method_exists($response, 'getStatusCode')
            ? $response->getStatusCode()
            : null;
    }

    private static function responseBytes($response): int
    {
        if (!is_object($response) || !method_exists($response, 'getContent')) {
            return 0;
        }

        $content = $response->getContent();

        return is_string($content) ? strlen($content) : 0;
    }
}
