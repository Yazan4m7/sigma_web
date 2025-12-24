<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Starting job types report test data insertion...\n";

// Check if test client exists
$client = DB::select("SELECT id FROM clients WHERE name = 'Test Client' LIMIT 1");
if (empty($client)) {
    // Insert test client
    DB::insert("INSERT INTO clients (name, email, phone, active, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())", [
        'Test Client',
        'test@example.com',
        '1234567890',
        1
    ]);
    $clientId = DB::getPdo()->lastInsertId();
    echo "Created test client with ID: {$clientId}\n";
} else {
    $clientId = $client[0]->id;
    echo "Using existing test client with ID: {$clientId}\n";
}

// Insert test case with delivery in November 2024
DB::insert("INSERT INTO cases (doctor_id, patient_name, actual_delivery_date, delivered_to_client, created_by, current_status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())", [
    $clientId,
    'Test Patient Job Types',
    '2024-11-15 10:00:00',
    1,
    1,
    'completed'
]);
$caseId = DB::getPdo()->lastInsertId();
echo "Created test case with ID: {$caseId}\n";

// Get a material that counts in job types report
$material = DB::select("SELECT id, name FROM materials WHERE count_in_job_types_report = 1 LIMIT 1");
if (empty($material)) {
    echo "No materials found with count_in_job_types_report = 1\n";
    exit;
}
$materialId = $material[0]->id;
$materialName = $material[0]->name;
echo "Using material ID: {$materialId} ({$materialName})\n";

// Insert jobs with different job types
// Job type 1 = Crown, 2 = Bridge, 3 = Implant, 4 = Abutment
$jobTypes = [
    ['type' => 1, 'units' => '11,12', 'expected_units' => 2], // Crown: 2 units
    ['type' => 2, 'units' => '13,14,15', 'expected_units' => 3], // Bridge: 3 units
    ['type' => 3, 'units' => '16', 'expected_units' => 1], // Implant: 1 unit
    ['type' => 4, 'units' => '17,18', 'expected_units' => 2], // Abutment: 2 units
];

$totalExpectedUnits = 0;
foreach ($jobTypes as $jobData) {
    DB::insert("INSERT INTO jobs (case_id, type, type_id, material_id, unit_num, stage, assignee, is_rejection, is_repeat, is_modification, is_redo, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())", [
        $caseId,
        $jobData['type'],
        1, // type_id
        $materialId,
        $jobData['units'],
        -1, // completed
        1, // assignee
        0, // is_rejection
        0, // is_repeat
        0, // is_modification
        0, // is_redo
        1  // is_active
    ]);
    $jobId = DB::getPdo()->lastInsertId();
    $totalExpectedUnits += $jobData['expected_units'];
    echo "Created job ID: {$jobId} with type: {$jobData['type']}, units: {$jobData['units']} ({$jobData['expected_units']} units)\n";
}

echo "Total expected units: {$totalExpectedUnits}\n";

// Now test the report methods using raw SQL to verify
echo "\nTesting report queries for November 2024:\n";

// Test numOfUnitsByJobType for each job type
foreach ($jobTypes as $jobData) {
    $type = $jobData['type'];
    $expected = $jobData['expected_units'];

    // Raw SQL equivalent of numOfUnitsByJobType method
    $result = DB::select("
        SELECT COUNT(*) as unit_count FROM (
            SELECT j.id, j.unit_num
            FROM jobs j
            INNER JOIN cases c ON j.case_id = c.id
            WHERE c.doctor_id = ?
            AND c.actual_delivery_date BETWEEN '2024-11-01 00:00:00' AND '2024-11-30 23:59:59'
            AND c.actual_delivery_date IS NOT NULL
            AND j.type = ?
            AND j.is_rejection = 0
            AND j.is_repeat = 0
            AND j.is_modification = 0
            AND j.is_redo = 0
        ) as subquery,
        JSON_TABLE(
            CONCAT('[\"', REPLACE(unit_num, ',', '\",\"'), '\"]'),
            '$[*]' COLUMNS (unit_value VARCHAR(10) PATH '$')
        ) as units
    ", [$clientId, $type]);

    $actual = $result[0]->unit_count ?? 0;
    echo "Job type {$type}: Expected {$expected} units, Actual {$actual} units - " . ($actual == $expected ? "PASS" : "FAIL") . "\n";
}

// Test numOfCasesByJobType
$caseResult = DB::select("
    SELECT COUNT(DISTINCT c.id) as case_count
    FROM cases c
    INNER JOIN jobs j ON c.id = j.case_id
    WHERE c.doctor_id = ?
    AND c.actual_delivery_date BETWEEN '2024-11-01 00:00:00' AND '2024-11-30 23:59:59'
    AND c.actual_delivery_date IS NOT NULL
    AND j.is_rejection = 0
    AND j.is_repeat = 0
    AND j.is_modification = 0
    AND j.is_redo = 0
", [$clientId]);

$actualCases = $caseResult[0]->case_count ?? 0;
echo "Cases count: Expected 1 case, Actual {$actualCases} cases - " . ($actualCases == 1 ? "PASS" : "FAIL") . "\n";

echo "\nJob types report test completed!\n";
