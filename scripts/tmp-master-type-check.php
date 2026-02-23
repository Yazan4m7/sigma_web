<?php
use Illuminate\Http\Request;
use App\Http\Controllers\ReportsController;
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$request = Request::create('/reports/master', 'GET', ['from' => '2026-02-01', 'to' => '2026-02-28', 'material_type' => [7]]);
$view = app(ReportsController::class)->masterReport($request);
$cases = ($view->getData()['cases'] ?? collect())->pluck('case_id')->sort()->values()->all();
echo implode(', ', $cases);
