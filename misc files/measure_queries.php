<?php
/**
 * SIGMA Database Query Performance Measurement
 *
 * This script measures query performance by enabling query logging
 * Run this BEFORE and AFTER adding indexes to compare
 *
 * Usage:
 *   php measure_queries.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

echo "======================================================================\n";
echo "SIGMA Database Query Performance Measurement\n";
echo "======================================================================\n\n";

// Enable query logging
DB::connection()->enableQueryLog();

// Clear any existing cache to ensure we measure fresh queries
Cache::flush();
echo "✓ Cache cleared\n";

$startTime = microtime(true);

// Simulate the dashboard queries
echo "\nExecuting dashboard queries...\n\n";

// Query 1: Get all cases with jobs (like the dashboard does)
echo "1. Loading cases with relationships... ";
$query1Start = microtime(true);

$cases = DB::table('cases as c')
    ->join('jobs as j', 'c.id', '=', 'j.case_id')
    ->whereIn('j.stage', [1, 2, 3, 4, 5, 6, 7, 8])
    ->whereNull('c.deleted_at')
    ->select('c.*')
    ->groupBy('c.id')
    ->get();

$query1Time = (microtime(true) - $query1Start) * 1000;
echo sprintf("%.2f ms (%d cases)\n", $query1Time, $cases->count());

// Query 2: Design stage active cases
echo "2. Design stage active cases... ";
$query2Start = microtime(true);

$designActive = DB::table('cases as c')
    ->join('jobs as j', 'c.id', '=', 'j.case_id')
    ->where('j.stage', 1)
    ->whereNotNull('j.assignee')
    ->whereNull('c.deleted_at')
    ->select('c.id', 'c.patient_name', 'c.doctor_id')
    ->groupBy('c.id', 'c.patient_name', 'c.doctor_id')
    ->get();

$query2Time = (microtime(true) - $query2Start) * 1000;
echo sprintf("%.2f ms (%d cases)\n", $query2Time, $designActive->count());

// Query 3: Milling stage active cases
echo "3. Milling stage active cases... ";
$query3Start = microtime(true);

$millingActive = DB::table('cases as c')
    ->join('jobs as j', 'c.id', '=', 'j.case_id')
    ->where('j.stage', 2)
    ->where('j.is_set', 1)
    ->whereNull('c.deleted_at')
    ->select('c.id', 'c.patient_name', 'c.doctor_id')
    ->groupBy('c.id', 'c.patient_name', 'c.doctor_id')
    ->get();

$query3Time = (microtime(true) - $query3Start) * 1000;
echo sprintf("%.2f ms (%d cases)\n", $query3Time, $millingActive->count());

// Query 4: Build statistics for all devices
echo "4. Build statistics... ";
$query4Start = microtime(true);

$buildStats = DB::table('builds')
    ->select(
        'device_used',
        DB::raw('SUM(CASE WHEN set_at IS NOT NULL AND started_at IS NULL AND finished_at IS NULL THEN 1 ELSE 0 END) as waiting_builds'),
        DB::raw('SUM(CASE WHEN set_at IS NOT NULL AND started_at IS NOT NULL AND finished_at IS NULL THEN 1 ELSE 0 END) as active_builds')
    )
    ->whereNull('deleted_at')
    ->groupBy('device_used')
    ->get();

$query4Time = (microtime(true) - $query4Start) * 1000;
echo sprintf("%.2f ms (%d devices)\n", $query4Time, $buildStats->count());

// Query 5: Jobs for a specific case and stage (assign operation)
echo "5. Assign operation query... ";
$query5Start = microtime(true);

$assignJobs = DB::table('jobs')
    ->where('case_id', 18494)
    ->where('stage', 1)
    ->whereNull('assignee')
    ->get();

$query5Time = (microtime(true) - $query5Start) * 1000;
echo sprintf("%.2f ms (%d jobs)\n", $query5Time, $assignJobs->count());

$totalTime = (microtime(true) - $startTime) * 1000;

// Get query log
$queries = DB::getQueryLog();

echo "\n======================================================================\n";
echo "RESULTS SUMMARY\n";
echo "======================================================================\n\n";

echo sprintf("Total execution time: %.2f ms\n", $totalTime);
echo sprintf("Total queries executed: %d\n\n", count($queries));

echo "Query breakdown:\n";
echo sprintf("  1. Cases with jobs: %.2f ms\n", $query1Time);
echo sprintf("  2. Design active: %.2f ms\n", $query2Time);
echo sprintf("  3. Milling active: %.2f ms\n", $query3Time);
echo sprintf("  4. Build statistics: %.2f ms\n", $query4Time);
echo sprintf("  5. Assign operation: %.2f ms\n\n", $query5Time);

// Show slowest queries
echo "Top 5 slowest queries:\n";
echo "----------------------\n";

$queriesWithTime = array_map(function($query) {
    return [
        'query' => $query['query'],
        'time' => $query['time'],
        'bindings' => $query['bindings']
    ];
}, $queries);

usort($queriesWithTime, function($a, $b) {
    return $b['time'] <=> $a['time'];
});

$topQueries = array_slice($queriesWithTime, 0, 5);

foreach ($topQueries as $index => $query) {
    echo sprintf("\n%d. %.2f ms\n", $index + 1, $query['time']);
    echo "   Query: " . substr($query['query'], 0, 100) . "...\n";
}

// Save results to file
$timestamp = date('Y-m-d_H-i-s');
$resultsFile = "query_performance_{$timestamp}.txt";

$output = "SIGMA Query Performance Test\n";
$output .= "Date: " . date('Y-m-d H:i:s') . "\n";
$output .= "======================================================================\n\n";
$output .= sprintf("Total execution time: %.2f ms\n", $totalTime);
$output .= sprintf("Total queries: %d\n\n", count($queries));
$output .= "Query breakdown:\n";
$output .= sprintf("  1. Cases with jobs: %.2f ms\n", $query1Time);
$output .= sprintf("  2. Design active: %.2f ms\n", $query2Time);
$output .= sprintf("  3. Milling active: %.2f ms\n", $query3Time);
$output .= sprintf("  4. Build statistics: %.2f ms\n", $query4Time);
$output .= sprintf("  5. Assign operation: %.2f ms\n\n", $query5Time);

$output .= "\nAll queries:\n";
$output .= "------------\n";
foreach ($queries as $index => $query) {
    $output .= sprintf("\n%d. %.2f ms\n", $index + 1, $query['time']);
    $output .= "   " . $query['query'] . "\n";
    if (!empty($query['bindings'])) {
        $output .= "   Bindings: " . json_encode($query['bindings']) . "\n";
    }
}

file_put_contents($resultsFile, $output);

echo "\n\n======================================================================\n";
echo "✓ Results saved to: {$resultsFile}\n";
echo "======================================================================\n\n";

echo "Next steps:\n";
echo "1. Save these results for comparison\n";
echo "2. Run add_performance_indexes.sql on your Windows MySQL\n";
echo "3. Run this script again to measure improvement\n\n";
