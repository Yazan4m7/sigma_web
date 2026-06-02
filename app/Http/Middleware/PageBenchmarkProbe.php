<?php

namespace App\Http\Middleware;

use App\Support\PageBenchmark;
use Closure;

class PageBenchmarkProbe
{
    public function handle($request, Closure $next, $label = 'unknown')
    {
        $isBenchmarking = PageBenchmark::bootIfActive($request);

        if ($isBenchmarking) {
            PageBenchmark::mark($request, 'web.' . $label . '.enter');
        }

        $response = $next($request);

        if ($isBenchmarking) {
            PageBenchmark::mark($request, 'web.' . $label . '.exit');
        }

        return $response;
    }
}
