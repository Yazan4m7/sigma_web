<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class OperationsDashboardCache
{
    private const GENERATION_KEY = 'operations_dashboard_v2_generation';

    public static function currentGeneration(): string
    {
        return (string) Cache::rememberForever(self::GENERATION_KEY, static function () {
            return (string) Str::uuid();
        });
    }

    public static function bumpGeneration(): string
    {
        $generation = (string) Str::uuid();

        Cache::forever(self::GENERATION_KEY, $generation);

        return $generation;
    }
}
