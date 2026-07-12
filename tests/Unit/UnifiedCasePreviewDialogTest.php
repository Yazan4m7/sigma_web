<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UnifiedCasePreviewDialogTest extends TestCase
{
    public function test_finishing_completion_previews_show_every_case_job(): void
    {
        $dashboard = file_get_contents(__DIR__ . '/../../resources/views/cases/admin-dashboardv2.blade.php');

        $this->assertSame(2, substr_count($dashboard, "\$caseCompletionJobs = \$key == 'finishing'"));
        $this->assertSame(2, substr_count($dashboard, ": \$case->jobs->where('stage', \$stage['numericStage'])"));
        $this->assertSame(2, substr_count($dashboard, "\$showJob = \$key == 'finishing'"));
    }

    public function test_case_preview_dialogs_share_the_shell_and_close_button(): void
    {
        $dashboard = file_get_contents(__DIR__ . '/../../resources/views/cases/admin-dashboardv2.blade.php');
        $cases = file_get_contents(__DIR__ . '/../../resources/views/cases/index.blade.php');
        $caseContent = file_get_contents(__DIR__ . '/../../resources/views/cases/partials/actions-modal-content.blade.php');
        $delivery = file_get_contents(__DIR__ . '/../../resources/views/delivery/delivery-schedule.blade.php');
        $css = file_get_contents(__DIR__ . '/../../public/assets/css/custom-styling.css');
        $legacyWorkflowCss = file_get_contents(__DIR__ . '/../../public/assets/css/v3styles.css');

        $this->assertSame(2, substr_count($dashboard, 'sigma-modal--case-preview-unified sigma-dialog-overlay'));
        $this->assertSame(2, substr_count($dashboard, 'data-backdrop="false" data-keyboard="true"'));
        $this->assertSame(2, substr_count($dashboard, '<x-sigma-close-button />'));
        $this->assertStringContainsString('<x-sigma-close-button />', $cases);
        $this->assertStringContainsString("#actionsDialog .sigma-close-button", $cases);
        $this->assertStringContainsString('<x-sigma-close-button />', $caseContent);
        $this->assertStringContainsString('<x-sigma-close-button />', $delivery);
        $this->assertStringContainsString('.sigma-dialog-overlay.sigma-modal--case-preview-unified.show .modal-content', $css);
        $this->assertStringContainsString('.sigma-modal--case-preview-unified .modal-dialog', $css);
        $this->assertStringContainsString('.sigma-close-button {', $css);
        $this->assertStringNotContainsString('.sigma-close-button {', $legacyWorkflowCss);
        $this->assertStringNotContainsString('case-preview-close', $cases);
        $this->assertStringNotContainsString('case-preview-close', $delivery);
    }
}
