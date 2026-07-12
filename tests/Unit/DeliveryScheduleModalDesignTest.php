<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class DeliveryScheduleModalDesignTest extends TestCase
{
    /** @test */
    public function delivery_actions_modal_reuses_the_cases_preview_shell_without_changing_actions(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/delivery/delivery-schedule.blade.php');
        $css = file_get_contents(__DIR__ . '/../../public/assets/css/custom-styling.css');

        $this->assertStringContainsString(
            "modal sigma-modal--delivery-schedule-actions sigma-modal--delivery-schedule-action sigma-dialog-overlay",
            $view
        );
        $this->assertStringContainsString("modal-header case-preview-header", $view);
        $this->assertStringContainsString("close case-preview-close", $view);
        $this->assertStringNotContainsString("<h5 class='modal-title'>Case Actions</h5>", $view);
        $this->assertStringContainsString(
            '.sigma-dialog-overlay.sigma-modal--delivery-schedule-actions.show .modal-content',
            $css
        );

        $this->assertStringContainsString("id='delivery-actions-view-voucher'", $view);
        $this->assertStringContainsString("id='delivery-actions-view-case'", $view);
        $this->assertStringContainsString("id='delivery-actions-edit-case'", $view);
        $this->assertStringContainsString("id='delivery-actions-edit-delivery'", $view);
        $this->assertStringContainsString("class='modal-footer case-actions-footer'", $view);
    }
}
