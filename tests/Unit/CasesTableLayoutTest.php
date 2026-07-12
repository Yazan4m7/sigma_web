<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CasesTableLayoutTest extends TestCase
{
    /** @test */
    public function cases_mobile_table_scrolls_inside_datatables_body_without_moving_pagination(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/cases/index.blade.php');

        $this->assertStringContainsString('"scrollX": isMobileLayout', $view);
        $this->assertStringContainsString('scrollEl.style.overflowX = \'visible\';', $view);
        $this->assertStringContainsString('.dataTables_scrollBody {', $view);
        $this->assertStringContainsString('overflow-x: auto !important;', $view);
        $this->assertStringContainsString('.dataTables_wrapper .dataTables_paginate {', $view);
        $this->assertStringContainsString('overflow-x: visible !important;', $view);
        $this->assertStringNotContainsString('scrollEl.style.overflowX = \'auto\';', $view);
    }

    /** @test */
    public function cases_datatables_scroll_header_keeps_original_header_style_without_duplicate_bar(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/cases/index.blade.php');

        $this->assertStringContainsString('#casesTable_wrapper .dataTables_scrollHead table thead th', $view);
        $this->assertStringContainsString('background-color: #408385 !important;', $view);
        $this->assertStringContainsString('color: #ffffff !important;', $view);
        $this->assertStringContainsString('#casesTable_wrapper .dataTables_scrollBody thead th', $view);
        $this->assertStringContainsString('height: 0 !important;', $view);
        $this->assertStringContainsString('padding-top: 0 !important;', $view);
        $this->assertStringContainsString('padding-bottom: 0 !important;', $view);
    }

    /** @test */
    public function cases_doctor_filter_dropdown_refits_while_body_container_stays_open(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/cases/index.blade.php');

        $this->assertStringContainsString('function isCasesDoctorDropdownOpen()', $view);
        $this->assertStringContainsString('.bs-container.bootstrap-select.clearOnAll.filter-input-global.show', $view);
        $this->assertStringContainsString("$( '.main-panel, .content, .cases-table-scroll' ).on( 'scroll.casesDoctorDropdown'", $view);
        $this->assertStringContainsString("transform: 'none'", $view);
        $this->assertStringContainsString('padding: 6px 60px 6px 16px !important;', $view);
        $this->assertStringContainsString('padding-right: 8px;', $view);
    }

    /** @test */
    public function cases_dialogs_share_the_central_overlay_and_entrance_motion(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/cases/index.blade.php');
        $css = file_get_contents(__DIR__ . '/../../public/assets/css/custom-styling.css');

        $this->assertStringContainsString('--sigma-dialog-overlay-color: rgba(0, 0, 0, 0.46);', $css);
        $this->assertStringContainsString('@keyframes sigmaDialogEnter', $css);
        $this->assertStringContainsString('@keyframes sigmaDialogOverlayEnter', $css);
        $this->assertStringContainsString('sigma-modal--cases-index-action sigma-dialog-overlay', $view);
        $this->assertStringContainsString("container: 'sigma-dialog-overlay'", $view);
        $this->assertStringContainsString("popup: 'sigma-dialog-entrance'", $view);
        $this->assertStringContainsString('function setCasesDialogScrollUnlocked(unlocked)', $view);
        $this->assertStringContainsString('body.sigma-dialog-scroll-unlocked', $css);
        $this->assertStringNotContainsString("document.documentElement.classList.toggle( 'sigma-dialog-scroll-unlocked'", $view);
        $this->assertStringNotContainsString('translateY(var(--sigma-dialog-enter-offset-y))', $css);
        $this->assertStringNotContainsString('background: rgba(15, 23, 42, 0.28);', $view);
    }
}
