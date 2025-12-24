<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Insert test client
$clientId = DB::table('clients')->insertGetId([
    'name' => 'Test Client for Units Report',
    'active' => 1,
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "Inserted client with ID: $clientId\n";

// Insert test material
$materialId = DB::table('materials')->insertGetId([
    'name' => 'Test Material',
    'count_in_units_counts_report' => 1,
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "Inserted material with ID: $materialId\n";

// Insert test case with actual_delivery_date in November 2024
$caseId = DB::table('cases')->insertGetId([
    'doctor_id' => $clientId,
    'patient_name' => 'Test Patient',
    'actual_delivery_date' => '2024-11-15 10:00:00',
    'current_status' => 'completed',
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "Inserted case with ID: $caseId\n";

// Insert test jobs with units
$jobs = [
    [
        'case_id' => $caseId,
        'type' => 1, // Crown
        'material_id' => $materialId,
        'unit_num' => '11,12,13', // 3 units
        'stage' => -1, // completed
        'is_rejection' => 0,
        'is_repeat' => 0,
        'is_modification' => 0,
        'is_redo' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'case_id' => $caseId,
        'type' => 2, // Bridge
        'material_id' => $materialId,
        'unit_num' => '21,22', // 2 units
        'stage' => -1,
        'is_rejection' => 0,
        'is_repeat' => 0,
        'is_modification' => 0,
        'is_redo' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

foreach ($jobs as $job) {
    $jobId = DB::table('jobs')->insertGetId($job);
    echo "Inserted job with ID: $jobId, units: {$job['unit_num']}\n";
}

echo "Test data inserted successfully!\n";
echo "Expected units for client $clientId, material $materialId, month 2024-11: 5\n";
