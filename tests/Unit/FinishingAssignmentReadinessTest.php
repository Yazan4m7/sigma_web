<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FinishingAssignmentReadinessTest extends TestCase
{
    public function test_finishing_assignment_does_not_wait_for_every_job_to_reach_finishing(): void
    {
        $controller = file_get_contents(__DIR__ . '/../../app/Http/Controllers/CaseController.php');
        $assignStart = strpos($controller, 'public function assignToMe');
        $assignEnd = strpos($controller, 'public function assignAndFinish', $assignStart);
        $assignMethod = substr($controller, $assignStart, $assignEnd - $assignStart);

        $this->assertStringNotContainsString('allUnitsAtFinishing()', $assignMethod);
        $this->assertStringContainsString("->where('stage', \$stage)", $assignMethod);
    }

    public function test_finishing_completion_keeps_its_readiness_safeguards(): void
    {
        $controller = file_get_contents(__DIR__ . '/../../app/Http/Controllers/CaseController.php');
        $dashboard = file_get_contents(__DIR__ . '/../../resources/views/cases/admin-dashboardv2.blade.php');
        $waitingTable = file_get_contents(__DIR__ . '/../../resources/views/cases/dashboards-partials/waiting-table.blade.php');

        $this->assertStringContainsString("if (!\$case->allUnitsAtFinishing())", $controller);
        $this->assertStringContainsString("if (\$notReadyA || !\$abutmentsReceived)", $dashboard);
        $this->assertStringNotContainsString('canAssignStageCase', $dashboard);
        $this->assertStringNotContainsString('canAssignStageCase', $waitingTable);
    }
}
