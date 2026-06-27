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

    /** @test */
    public function operations_dashboard_case_completion_jobs_use_fixed_columns_and_note_direction_classes(): void
    {
        $views = [
            file_get_contents(__DIR__ . '/../../resources/views/cases/dashboards-partials/active-table.blade.php'),
            file_get_contents(__DIR__ . '/../../resources/views/cases/dashboards-partials/waiting-table.blade.php'),
            file_get_contents(__DIR__ . '/../../resources/views/cases/admin-dashboardv2.blade.php')
                . file_get_contents(__DIR__ . '/../../resources/views/cases/dashboards-partials/case-dialog-notes.blade.php'),
        ];

        foreach ($views as $view) {
            $this->assertStringContainsString('$jobLeadLine = trim((string) $job->unit_num);', $view);
            $this->assertStringContainsString('trim((string) $job->unit_num)', $view);
            $this->assertStringContainsString('$jobTypeLine = trim((string) $jobTypeName);', $view);
            $this->assertStringContainsString('$jobMaterialLine = trim((string) $materialName);', $view);
            $this->assertStringContainsString('$jobColorLine = trim((string) $colorLabel);', $view);
            $this->assertStringContainsString('$jobStyleLine = trim(implode(', $view);
            $this->assertStringContainsString('trim((string) $colorLabel)', $view);
            $this->assertStringContainsString('trim((string) $styleLabel)', $view);
            $this->assertStringContainsString('class="sigma-case-job-row sigma-case-job-card"', $view);
            $this->assertStringContainsString('class="sigma-case-job-cell sigma-case-job-cell--lead"', $view);
            $this->assertStringContainsString('class="sigma-case-job-cell sigma-case-job-cell--type"', $view);
            $this->assertStringContainsString('class="sigma-case-job-cell sigma-case-job-cell--material"', $view);
            $this->assertStringContainsString('class="sigma-case-job-cell sigma-case-job-cell--color"', $view);
            $this->assertStringContainsString('class="sigma-case-job-cell sigma-case-job-cell--style"', $view);
            $this->assertStringNotContainsString('$jobBadgeLabels = array_values(array_filter([', $view);
            $this->assertStringNotContainsString("{{ implode(' - ', \$fullJobParts) }}", $view);
            $this->assertStringContainsString('$noteHasArabic = preg_match(', $view);
            $this->assertStringContainsString('$noteHasLatin = preg_match(', $view);
            $this->assertStringContainsString('$noteDirectionClass = $noteHasArabic && $noteHasLatin', $view);
            $this->assertStringContainsString('class="noteText sigma-case-note-text {{ $noteDirectionClass }}"', $view);
        }

        $dashboard = $views[2];
        $this->assertStringContainsString('.sigma-case-job-card', $dashboard);
        $this->assertStringContainsString('grid-template-columns:minmax(0, 1fr) 80px 100px 48px 62px;', $dashboard);
        $this->assertStringContainsString('grid-template-columns: minmax(0, 1fr) 72px 106px 40px 56px;', $dashboard);
        $this->assertStringContainsString('.sigma-case-job-cell + .sigma-case-job-cell', $dashboard);
        $this->assertStringContainsString('display: block;', $dashboard);
        $this->assertStringContainsString('max-width: 100%;', $dashboard);
        $this->assertStringContainsString('justify-content: flex-start;', $dashboard);
        $this->assertStringContainsString('text-align: left;', $dashboard);
        $this->assertStringContainsString('text-overflow: ellipsis;', $dashboard);
        $this->assertStringContainsString('padding-left: 15px;', $dashboard);
        $this->assertStringContainsString('.sigma-case-note-text', $dashboard);
        $this->assertStringContainsString('direction: ltr !important;', $dashboard);
        $this->assertStringContainsString('.sigma-case-note-text--mixed', $dashboard);
        $this->assertStringContainsString('direction: inherit !important;', $dashboard);
        $this->assertStringContainsString('class="sigma-case-job-tooltip"', $dashboard);
        $this->assertStringContainsString('.sigma-case-job-tooltip', $dashboard);
        $this->assertStringContainsString('Updated D.Date', $views[2]);
        $this->assertStringContainsString('.sigma-case-delivery-notes-box', $dashboard);
    }

    /** @test */
    public function shared_modal_styles_do_not_allow_operations_job_cells_to_wrap(): void
    {
        $css = file_get_contents(__DIR__ . '/../../public/assets/css/custom-styling.css');

        $this->assertStringContainsString('.modal .modal-body .sigma-case-job-card > .sigma-case-job-cell', $css);
        $this->assertStringContainsString('white-space: nowrap !important;', $css);
        $this->assertStringContainsString('overflow: hidden !important;', $css);
        $this->assertStringContainsString('text-overflow: ellipsis !important;', $css);
        $this->assertStringContainsString('.modal .modal-body .sigma-case-job-card > .sigma-case-job-cell--color', $css);
        $this->assertStringContainsString('.modal .modal-body .sigma-case-job-card > .sigma-case-job-cell--style', $css);
        $this->assertStringContainsString('text-align: center !important;', $css);
    }

    /** @test */
    public function operations_dashboard_case_completion_scroll_area_uses_custom_scrollbar(): void
    {
        $dashboard = file_get_contents(__DIR__ . '/../../resources/views/cases/admin-dashboardv2.blade.php');

        $this->assertStringContainsString('.sigma-modal--cases-dashboard-case-completion .scrollable-content', $dashboard);
        $this->assertStringContainsString('.sigma-modal--dashboard-active-case-actions .scrollable-content', $dashboard);
        $this->assertStringContainsString('scrollbar-width: thin;', $dashboard);
        $this->assertStringContainsString('scrollbar-color: #1e9ba7 #eaf5f7;', $dashboard);
        $this->assertStringContainsString('.sigma-modal--cases-dashboard-case-completion .scrollable-content::-webkit-scrollbar', $dashboard);
        $this->assertStringContainsString('.sigma-modal--cases-dashboard-case-completion .scrollable-content::-webkit-scrollbar-thumb', $dashboard);
        $this->assertStringContainsString('background: linear-gradient(180deg, #2aa8b2, #1e7f8a);', $dashboard);
    }
}
