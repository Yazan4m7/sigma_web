<?php
// Test master report filters
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== MASTER REPORT FILTER TEST ===\n\n";

// Test 1: Basic date range filter
echo "TEST 1: Basic date range (2025-01-01 to 2025-12-31)\n";
$request = new \Illuminate\Http\Request([
    'generate_report' => '1',
    'from' => '2025-01-01',
    'to' => '2025-12-31'
]);
$controller = new \App\Http\Controllers\ReportsController();
try {
    $response = $controller->masterReport($request);
    $data = $response->getData();
    echo "✓ Request processed successfully\n";
    echo "  Cases found: " . $data['cases']->count() . "\n\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n\n";
}

// Test 2: Filter by specific doctor
echo "TEST 2: Filter by doctor ID=1\n";
$request = new \Illuminate\Http\Request([
    'generate_report' => '1',
    'from' => '2025-01-01',
    'to' => '2025-12-31',
    'doctor' => [1]
]);
try {
    $response = $controller->masterReport($request);
    $data = $response->getData();
    echo "✓ Request processed successfully\n";
    echo "  Cases found: " . $data['cases']->count() . "\n";
    if ($data['cases']->count() > 0) {
        echo "  First case doctor: " . $data['cases']->first()->client->rep_doctor . "\n";
    }
    echo "\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Filter by material
echo "TEST 3: Filter by material ID=1\n";
$request = new \Illuminate\Http\Request([
    'generate_report' => '1',
    'from' => '2025-01-01',
    'to' => '2025-12-31',
    'material' => [1]
]);
try {
    $response = $controller->masterReport($request);
    $data = $response->getData();
    echo "✓ Request processed successfully\n";
    echo "  Cases found: " . $data['cases']->count() . "\n\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n\n";
}

// Test 4: Multiple filters
echo "TEST 4: Multiple filters (doctor=1, material=1)\n";
$request = new \Illuminate\Http\Request([
    'generate_report' => '1',
    'from' => '2025-01-01',
    'to' => '2025-12-31',
    'doctor' => [1],
    'material' => [1]
]);
try {
    $response = $controller->masterReport($request);
    $data = $response->getData();
    echo "✓ Request processed successfully\n";
    echo "  Cases found: " . $data['cases']->count() . "\n\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n\n";
}

echo "=== TEST COMPLETE ===\n";
