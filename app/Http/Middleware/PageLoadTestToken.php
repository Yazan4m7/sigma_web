<?php

namespace App\Http\Middleware;

use App\Support\PageBenchmark;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PageLoadTestToken
{
    public function handle($request, Closure $next)
    {
        $isBenchmarking = PageBenchmark::bootIfActive($request);
        $token = $request->query('__plt');

        if ($token) {
            $cacheKey = 'page_load_test_token:' . $token;
            $payload = Cache::pull($cacheKey);
            if ($payload && isset($payload['user_id'])) {
                Auth::onceUsingId($payload['user_id']);
            }

            if ($isBenchmarking) {
                PageBenchmark::mark($request, 'middleware.page-load-test-token.resolved', [
                    'token_present' => true,
                    'resolved_user_id' => $payload['user_id'] ?? null,
                ]);
            }
        } elseif ($isBenchmarking) {
            PageBenchmark::mark($request, 'middleware.page-load-test-token.skipped', [
                'token_present' => false,
            ]);
        }

        return $next($request);
    }
}
