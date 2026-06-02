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
}
