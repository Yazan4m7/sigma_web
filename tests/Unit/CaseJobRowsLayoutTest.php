<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CaseJobRowsLayoutTest extends TestCase
{
    /** @test */
    public function cases_action_job_rows_split_units_from_job_details(): void
    {
        $partial = file_get_contents(__DIR__ . '/../../resources/views/cases/partials/actions-modal-content.blade.php');
        $index = file_get_contents(__DIR__ . '/../../resources/views/cases/index.blade.php');

        $this->assertStringContainsString('font-size: 15px !important;', $partial);
        $this->assertStringContainsString('class="sigma-case-job-units"', $partial);
        $this->assertStringContainsString('{{ $jobUnits }}', $partial);
        $this->assertStringContainsString('class="sigma-case-job-separator"', $partial);
        $this->assertStringContainsString('-</span>', $partial);
        $this->assertStringContainsString('class="sigma-case-job-details"', $partial);
        $this->assertStringContainsString("{{ implode(' - ', \$jobDetailParts) }}", $partial);
        $this->assertStringContainsString('.sigma-case-job-units', $partial);
        $this->assertDoesNotMatchRegularExpression('/\\.sigma-case-job-units\\s*\\{[^}]*font-weight/s', $partial);
        $this->assertStringContainsString('font-size: 15px !important;', $index);
        $this->assertStringContainsString('.sigma-case-job-units', $index);
        $this->assertStringContainsString('.sigma-case-job-separator', $index);
        $this->assertStringContainsString('.sigma-case-job-details', $index);
    }
}
