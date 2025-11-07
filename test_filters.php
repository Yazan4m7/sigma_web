<?php
// Comprehensive master report filter validation test
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== MASTER REPORT COMPREHENSIVE FILTER VALIDATION ===\n\n";

$controller = new \App\Http\Controllers\ReportsController();
$allPassed = true;

// Helper function to validate results
function validateResults($testName, $cases, $validationCallback) {
    global $allPassed;

    echo "TEST: $testName\n";
    echo "  Cases returned: " . $cases->count() . "\n";

    if ($cases->count() === 0) {
        echo "  ⚠ No cases found - cannot validate\n\n";
        return;
    }

    $errors = [];
    foreach ($cases as $index => $case) {
        $error = $validationCallback($case, $index);
        if ($error) {
            $errors[] = $error;
        }
    }

    if (empty($errors)) {
        echo "  ✓ ALL CASES VALIDATED SUCCESSFULLY\n\n";
    } else {
        echo "  ✗ VALIDATION FAILED:\n";
        foreach (array_slice($errors, 0, 5) as $error) {  // Show first 5 errors
            echo "    - $error\n";
        }
        if (count($errors) > 5) {
            echo "    ... and " . (count($errors) - 5) . " more errors\n";
        }
        echo "\n";
        $allPassed = false;
    }
}

// TEST 1: Date range filter
echo "═══════════════════════════════════════════\n";
$from = '2025-01-01';
$to = '2025-03-31';
$request = new \Illuminate\Http\Request([
    'generate_report' => '1',
    'from' => $from,
    'to' => $to
]);
$response = $controller->masterReport($request);
$data = $response->getData();

validateResults("Date Range Filter ($from to $to)", $data['cases'], function($case) use ($from, $to) {
    $caseDate = \Carbon\Carbon::parse($case->created_at)->format('Y-m-d');
    $fromDate = \Carbon\Carbon::parse($from);
    $toDate = \Carbon\Carbon::parse($to)->endOfDay();
    $caseDateCarbon = \Carbon\Carbon::parse($case->created_at);

    if ($caseDateCarbon->lt($fromDate) || $caseDateCarbon->gt($toDate)) {
        return "Case #{$case->id} date {$caseDate} is outside range {$from} to {$to}";
    }
    return null;
});

// TEST 2: Filter by specific doctor
echo "═══════════════════════════════════════════\n";
$doctorId = 1;
$request = new \Illuminate\Http\Request([
    'generate_report' => '1',
    'from' => '2025-01-01',
    'to' => '2025-12-31',
    'doctor' => [$doctorId]
]);
$response = $controller->masterReport($request);
$data = $response->getData();

validateResults("Doctor Filter (ID=$doctorId)", $data['cases'], function($case) use ($doctorId) {
    if ($case->client && $case->client->rep_doctor != $doctorId) {
        return "Case #{$case->id} has doctor ID {$case->client->rep_doctor}, expected $doctorId";
    }
    return null;
});

// TEST 3: Filter by material
echo "═══════════════════════════════════════════\n";
$materialId = 1;
$request = new \Illuminate\Http\Request([
    'generate_report' => '1',
    'from' => '2025-01-01',
    'to' => '2025-12-31',
    'material' => [$materialId]
]);
$response = $controller->masterReport($request);
$data = $response->getData();

validateResults("Material Filter (ID=$materialId)", $data['cases'], function($case) use ($materialId) {
    $hasMaterial = false;
    foreach ($case->jobs as $job) {
        if ($job->material_id == $materialId) {
            $hasMaterial = true;
            break;
        }
    }
    if (!$hasMaterial) {
        $materials = $case->jobs->pluck('material_id')->unique()->implode(', ');
        return "Case #{$case->id} has materials [$materials], expected to include $materialId";
    }
    return null;
});

// TEST 4: Filter by job type
echo "═══════════════════════════════════════════\n";
$jobTypeId = 1;
$request = new \Illuminate\Http\Request([
    'generate_report' => '1',
    'from' => '2025-01-01',
    'to' => '2025-12-31',
    'job_type' => [$jobTypeId]
]);
$response = $controller->masterReport($request);
$data = $response->getData();

validateResults("Job Type Filter (ID=$jobTypeId)", $data['cases'], function($case) use ($jobTypeId) {
    $hasJobType = false;
    foreach ($case->jobs as $job) {
        if ($job->job_type_id == $jobTypeId) {
            $hasJobType = true;
            break;
        }
    }
    if (!$hasJobType) {
        $jobTypes = $case->jobs->pluck('job_type_id')->unique()->implode(', ');
        return "Case #{$case->id} has job types [$jobTypes], expected to include $jobTypeId";
    }
    return null;
});

// TEST 5: Filter by status (completed)
echo "═══════════════════════════════════════════\n";
$request = new \Illuminate\Http\Request([
    'generate_report' => '1',
    'from' => '2025-01-01',
    'to' => '2025-12-31',
    'status' => ['completed']
]);
$response = $controller->masterReport($request);
$data = $response->getData();

validateResults("Status Filter (completed)", $data['cases'], function($case) {
    if ($case->stage != 9.9) {
        return "Case #{$case->id} has stage {$case->stage}, expected 9.9 (completed)";
    }
    return null;
});

// TEST 6: Employee filter - Design stage
echo "═══════════════════════════════════════════\n";
// First, get a user who has design permission
$designUser = \App\User::whereHas('permissions', function($q) {
    $q->where('permission_id', 1);
})->first();

if ($designUser) {
    $request = new \Illuminate\Http\Request([
        'generate_report' => '1',
        'from' => '2025-01-01',
        'to' => '2025-12-31',
        'employees' => [
            ['stage' => 'design', 'employee_id' => $designUser->id]
        ]
    ]);
    $response = $controller->masterReport($request);
    $data = $response->getData();

    validateResults("Employee Filter (Design, User ID={$designUser->id} - {$designUser->first_name})", $data['cases'], function($case) use ($designUser) {
        // Check case logs for this employee in design stage (stage 1)
        $hasEmployee = $case->caseLogs->filter(function($log) use ($designUser) {
            return $log->user_id == $designUser->id &&
                   ($log->action_stage == 1 || $log->action_stage == '1');
        })->count() > 0;

        if (!$hasEmployee) {
            $designLogs = $case->caseLogs->where('action_stage', 1)->pluck('user_id')->unique()->implode(', ');
            return "Case #{$case->id} design logs show users [$designLogs], expected user {$designUser->id}";
        }
        return null;
    });
} else {
    echo "TEST: Employee Filter (Design)\n";
    echo "  ⚠ No design users found in database\n\n";
}

// TEST 7: Multiple filters combined (doctor + material + status)
echo "═══════════════════════════════════════════\n";
$doctorId = 1;
$materialId = 1;
$request = new \Illuminate\Http\Request([
    'generate_report' => '1',
    'from' => '2025-01-01',
    'to' => '2025-12-31',
    'doctor' => [$doctorId],
    'material' => [$materialId],
    'status' => ['completed']
]);
$response = $controller->masterReport($request);
$data = $response->getData();

validateResults("Multiple Filters (Doctor=$doctorId, Material=$materialId, Status=completed)", $data['cases'], function($case) use ($doctorId, $materialId) {
    // Check doctor
    if ($case->client && $case->client->rep_doctor != $doctorId) {
        return "Case #{$case->id} has doctor ID {$case->client->rep_doctor}, expected $doctorId";
    }

    // Check material
    $hasMaterial = false;
    foreach ($case->jobs as $job) {
        if ($job->material_id == $materialId) {
            $hasMaterial = true;
            break;
        }
    }
    if (!$hasMaterial) {
        return "Case #{$case->id} does not have material $materialId";
    }

    // Check status
    if ($case->stage != 9.9) {
        return "Case #{$case->id} has stage {$case->stage}, expected 9.9 (completed)";
    }

    return null;
});

// TEST 8: Amount range filter
echo "═══════════════════════════════════════════\n";
$amountFrom = 100;
$amountTo = 500;
$request = new \Illuminate\Http\Request([
    'generate_report' => '1',
    'from' => '2025-01-01',
    'to' => '2025-12-31',
    'amount_from' => $amountFrom,
    'amount_to' => $amountTo
]);
$response = $controller->masterReport($request);
$data = $response->getData();

validateResults("Amount Range Filter ($amountFrom to $amountTo)", $data['cases'], function($case) use ($amountFrom, $amountTo) {
    $totalAmount = $case->jobs->sum('price');
    if ($totalAmount < $amountFrom || $totalAmount > $amountTo) {
        return "Case #{$case->id} has amount {$totalAmount}, expected between {$amountFrom} and {$amountTo}";
    }
    return null;
});

// TEST 9: Units range filter
echo "═══════════════════════════════════════════\n";
$unitsFrom = 1;
$unitsTo = 5;
$request = new \Illuminate\Http\Request([
    'generate_report' => '1',
    'from' => '2025-01-01',
    'to' => '2025-12-31',
    'units_from' => $unitsFrom,
    'units_to' => $unitsTo
]);
$response = $controller->masterReport($request);
$data = $response->getData();

validateResults("Units Range Filter ($unitsFrom to $unitsTo)", $data['cases'], function($case) use ($unitsFrom, $unitsTo) {
    $totalUnits = $case->jobs->sum('units');
    if ($totalUnits < $unitsFrom || $totalUnits > $unitsTo) {
        return "Case #{$case->id} has {$totalUnits} units, expected between {$unitsFrom} and {$unitsTo}";
    }
    return null;
});

// TEST 10: Device filter - Milling stage
echo "═══════════════════════════════════════════\n";
$millingDevice = \App\device::where('type', 2)->first(); // type 2 = milling

if ($millingDevice) {
    $request = new \Illuminate\Http\Request([
        'generate_report' => '1',
        'from' => '2025-01-01',
        'to' => '2025-12-31',
        'devices' => [
            ['stage' => 'milling', 'device_id' => $millingDevice->id]
        ]
    ]);
    $response = $controller->masterReport($request);
    $data = $response->getData();

    validateResults("Device Filter (Milling, Device ID={$millingDevice->id} - {$millingDevice->name})", $data['cases'], function($case) use ($millingDevice) {
        $hasDevice = false;
        foreach ($case->jobs as $job) {
            if ($job->millingBuild && $job->millingBuild->device_used == $millingDevice->id) {
                $hasDevice = true;
                break;
            }
        }
        if (!$hasDevice) {
            $devices = [];
            foreach ($case->jobs as $job) {
                if ($job->millingBuild) {
                    $devices[] = $job->millingBuild->device_used;
                }
            }
            $devicesList = implode(', ', array_unique($devices));
            return "Case #{$case->id} milling devices [$devicesList], expected device {$millingDevice->id}";
        }
        return null;
    });
} else {
    echo "TEST: Device Filter (Milling)\n";
    echo "  ⚠ No milling devices found in database\n\n";
}

// SUMMARY
echo "═══════════════════════════════════════════\n";
echo "═══════════════════════════════════════════\n";
if ($allPassed) {
    echo "✓✓✓ ALL TESTS PASSED ✓✓✓\n";
} else {
    echo "✗✗✗ SOME TESTS FAILED ✗✗✗\n";
}
echo "═══════════════════════════════════════════\n";
