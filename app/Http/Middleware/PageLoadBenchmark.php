<?php

namespace App\Http\Middleware;

use App\Support\PageBenchmark;
use Closure;

class PageLoadBenchmark
{
    public function handle($request, Closure $next, $profile = null)
    {
        $isBenchmarking = PageBenchmark::bootIfActive($request, $profile);

        if ($isBenchmarking) {
            PageBenchmark::mark($request, 'route.middleware.enter', [
                'profile' => $profile,
            ]);
        }

        $response = $next($request);

        if ($isBenchmarking) {
            $summary = PageBenchmark::finish($request, $response);

            if ($summary && is_object($response) && isset($response->headers)) {
                $response->headers->set('Server-Timing', PageBenchmark::serverTimingHeader($summary));
            }
        }

        return $response;
    }
}
