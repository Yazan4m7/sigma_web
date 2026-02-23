<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ReportsController;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

function idByName(string $table, string $column, string $name): int
{
    $id = DB::table($table)->where($column, $name)->value('id');
    if (!$id) {
        throw new RuntimeException("Missing {$table} row for {$name}");
    }
    return (int) $id;
}

$ids = [
    'clientA' => idByName('clients', 'name', 'MR Clinic Alpha'),
    'clientB' => idByName('clients', 'name', 'MR Clinic Beta'),
    'matZirc' => idByName('materials', 'name', 'MR Material Zirconia'),
    'matResin' => idByName('materials', 'name', 'MR Material Resin'),
    'matMetal' => idByName('materials', 'name', 'MR Material Metal'),
    'typeZircLayered' => idByName('types', 'name', 'MR Type Zirconia Layered'),
    'typeMetalCoCr' => idByName('types', 'name', 'MR Type Metal CoCr'),
    'jtBridge' => idByName('job_types', 'name', 'MR Job Bridge'),
    'jtCrown' => idByName('job_types', 'name', 'MR Job Crown'),
    'abutAlpha' => idByName('abutments', 'name', 'MR Abutment Alpha'),
    'abutBeta' => idByName('abutments', 'name', 'MR Abutment Beta'),
    'implantAlpha' => idByName('implants', 'name', 'MR Implant Alpha'),
    'implantBeta' => idByName('implants', 'name', 'MR Implant Beta'),
    'causeFracture' => idByName('failure_causes', 'text', 'MR Failure: Fracture'),
    'userPrinting' => idByName('users', 'username', 'mr_report_printing'),
    'userMilling' => idByName('users', 'username', 'mr_report_milling'),
    'devicePrint' => idByName('devices', 'name', 'MR Device Print A'),
    'deviceMill' => idByName('devices', 'name', 'MR Device Mill A'),
];

$baseDate = [
    'from' => '2026-02-01',
    'to' => '2026-02-28',
];

$tries = [
    ['label' => '1. Date range only (Feb 2026)', 'params' => []],
    ['label' => '2. Date range only (Dec 2025)', 'params' => ['from' => '2025-12-01', 'to' => '2025-12-31']],
    ['label' => '3. Doctor = MR Clinic Alpha', 'params' => ['doctor' => [$ids['clientA']]]],
    ['label' => '4. Doctor = MR Clinic Beta', 'params' => ['doctor' => [$ids['clientB']]]],
    ['label' => '5. Material = MR Material Zirconia', 'params' => ['material' => [$ids['matZirc']]]],
    ['label' => '6. Material = MR Material Metal', 'params' => ['material' => [$ids['matMetal']]]],
    ['label' => '7. Job Type = MR Job Bridge', 'params' => ['job_type' => [$ids['jtBridge']]]],
    ['label' => '8. Material Type = MR Type Zirconia Layered', 'params' => ['material_type' => [$ids['typeZircLayered']]]],
    ['label' => '9. Failure Type = MR Failure: Fracture', 'params' => ['failure_type' => [$ids['causeFracture']]]],
    ['label' => '10. Abutments = MR Abutment Alpha', 'params' => ['abutments' => [$ids['abutAlpha']]]],
    ['label' => '11. Abutments = MR Abutment Beta', 'params' => ['abutments' => [$ids['abutBeta']]]],
    ['label' => '12. Implants = MR Implant Alpha', 'params' => ['implants' => [$ids['implantAlpha']]]],
    ['label' => '13. Implants = MR Implant Beta', 'params' => ['implants' => [$ids['implantBeta']]]],
    ['label' => '14. Workflow Stage = 3D Printing', 'params' => ['status' => ['3']]],
    ['label' => '15. Workflow Stage = Milling', 'params' => ['status' => ['2']]],
    ['label' => '16. Completion = Completed', 'params' => ['show_completed' => 'completed']],
    ['label' => '17. Invoice Amount 100..200', 'params' => ['amount_from' => 100, 'amount_to' => 200]],
    ['label' => '18. Units 5..5', 'params' => ['units_from' => 5, 'units_to' => 5]],
    ['label' => '19. Employee: Printing stage + mr_report_printing', 'params' => [
        'employee_filters' => [
            ['stage' => 'printing', 'employee_id' => $ids['userPrinting']],
        ],
    ]],
    ['label' => '20. Employees: Printing + Milling', 'params' => [
        'employee_filters' => [
            ['stage' => 'printing', 'employee_id' => $ids['userPrinting']],
            ['stage' => 'milling', 'employee_id' => $ids['userMilling']],
        ],
    ]],
    ['label' => '21. Device: Printing + MR Device Print A', 'params' => [
        'device_filters' => [
            ['stage' => 'printing', 'device_id' => $ids['devicePrint']],
        ],
    ]],
    ['label' => '22. Device: Milling + MR Device Mill A', 'params' => [
        'device_filters' => [
            ['stage' => 'milling', 'device_id' => $ids['deviceMill']],
        ],
    ]],
    ['label' => '23. Extreme combo', 'params' => [
        'show_completed' => 'in_progress',
        'status' => ['3'],
        'units_from' => 5,
        'units_to' => 5,
        'employee_filters' => [
            ['stage' => 'printing', 'employee_id' => $ids['userPrinting']],
        ],
        'device_filters' => [
            ['stage' => 'printing', 'device_id' => $ids['devicePrint']],
        ],
    ]],
    ['label' => '24. Combo: Doctor Beta + Zirconia + Milling', 'params' => [
        'doctor' => [$ids['clientB']],
        'material' => [$ids['matZirc']],
        'status' => ['2'],
    ]],
    ['label' => '25. Combo: Doctor Alpha + Metal + Pressing', 'params' => [
        'doctor' => [$ids['clientA']],
        'material' => [$ids['matMetal']],
        'status' => ['5'],
    ]],
];

foreach ($tries as $try) {
    $params = $try['params'];
    if (!isset($params['from']) && !isset($params['to'])) {
        $params = array_merge($baseDate, $params);
    }

    $request = Request::create('/reports/master', 'GET', $params);
    $view = app(ReportsController::class)->masterReport($request);
    $data = $view->getData();
    $cases = $data['cases'] ?? collect();
    $patientNames = $cases->pluck('patient_name')->sort()->values()->all();

    echo $try['label'] . PHP_EOL;
    echo '  Result: ' . (empty($patientNames) ? '[]' : implode(', ', $patientNames)) . PHP_EOL;
}
