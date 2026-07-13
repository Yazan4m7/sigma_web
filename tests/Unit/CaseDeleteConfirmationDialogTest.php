<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CaseDeleteConfirmationDialogTest extends TestCase
{
    /** @test */
    public function case_delete_uses_the_same_shared_themed_modal_on_cases_and_master_report(): void
    {
        $casesView = file_get_contents(__DIR__ . '/../../resources/views/cases/index.blade.php');
        $casesContent = file_get_contents(__DIR__ . '/../../resources/views/cases/partials/actions-modal-content.blade.php');
        $masterReportView = file_get_contents(__DIR__ . '/../../resources/views/reports/master-report.blade.php');
        $component = file_get_contents(__DIR__ . '/../../resources/views/components/case-delete-confirmation-dialog.blade.php');
        $css = file_get_contents(__DIR__ . '/../../public/assets/css/custom-styling.css');

        $this->assertStringContainsString('<x-case-delete-confirmation-dialog />', $casesView);
        $this->assertStringContainsString('<x-case-delete-confirmation-dialog />', $masterReportView);
        $this->assertStringContainsString('id="caseDeleteConfirmationDialog"', $component);
        $this->assertStringContainsString('role="alertdialog"', $component);
        $this->assertStringContainsString('modal fade sigma-modal--case-delete-confirmation sigma-dialog-overlay', $component);
        $this->assertStringContainsString('<x-sigma-close-button />', $component);
        $this->assertStringContainsString('id="confirmCaseDeleteButton"', $component);
        $this->assertStringContainsString('id="cancelCaseDeleteButton"', $component);
        $this->assertStringContainsString('sigma-action-btn js-case-delete', $casesContent);
        $this->assertStringContainsString('sigma-action-btn js-case-delete', $masterReportView);
        $this->assertStringContainsString(".on( 'click.sigmaCaseDeleteRequest', '.js-case-delete'", $component);
        $this->assertStringContainsString("window.location.assign( url );", $component);
        $this->assertStringContainsString("hidden.bs.modal.sigmaCaseDeleteNavigate", $component);
        $this->assertStringContainsString('.sigma-modal--case-delete-confirmation.show .modal-content', $css);
        $this->assertStringContainsString('transition: opacity var(--sigma-dialog-enter-duration)', $css);
        $this->assertStringNotContainsString('window.Swal || window.swal', $casesView);
        $this->assertStringNotContainsString('caseDelConfirmation(event)', $casesContent);
        $this->assertStringNotContainsString('caseDelConfirmation', $masterReportView);
        $this->assertStringNotContainsString('swal.fire', $masterReportView);
    }
}
