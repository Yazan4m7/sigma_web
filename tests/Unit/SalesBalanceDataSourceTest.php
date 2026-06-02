<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SalesBalanceDataSourceTest extends TestCase
{
    /** @test */
    public function sales_dialog_uses_the_calculated_current_balance(): void
    {
        $controller = file_get_contents(__DIR__ . '/../../app/Http/Controllers/ClientsController.php');
        $view = file_get_contents(__DIR__ . '/../../resources/views/clients/sales.blade.php');

        $this->assertStringContainsString(
            '$this->attachClientBalances($sales, now()->toDateString()',
            $controller
        );
        $this->assertStringContainsString(
            '$doctor->display_balance ?? $doctor->balance',
            $view
        );
    }

    /** @test */
    public function sales_dialog_keeps_doctor_and_balance_side_by_side_on_mobile(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/clients/sales.blade.php');

        $this->assertStringContainsString(
            "'col-8 col-md-6' : 'col-12'",
            $view
        );
        $this->assertStringContainsString(
            'class="col-4 col-md-6 doctor-actions-summary-col"',
            $view
        );
    }

    /** @test */
    public function sales_table_uses_a_compact_phone_subtitle_and_hides_mobile_sort_arrows(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/clients/sales.blade.php');

        $this->assertStringContainsString('@media (min-width: 992px)', $view);
        $this->assertStringContainsString(
            '.sales-page-wrapper .container.full-width.doctor-table-shell',
            $view
        );
        $this->assertStringContainsString('width: calc(100% - 30px) !important;', $view);
        $this->assertStringContainsString(
            '.sales-page-wrapper table#my-table.dataTable tbody td.sales-doctor-col {',
            $view
        );
        $this->assertStringContainsString('padding-left: 24px !important;', $view);
        $this->assertStringContainsString('white-space: normal !important;', $view);
        $this->assertStringContainsString('vertical-align: bottom;', $view);
        $this->assertStringContainsString(
            '<th class="table-head sigma-head-left">Days Since <span class="header__sub">Last Invoice</span></th>',
            $view
        );
        $this->assertStringNotContainsString('header__main', $view);
        $this->assertStringContainsString('.header__sub', $view);
        $this->assertStringContainsString('font-size: 0.8em;', $view);
        $this->assertStringContainsString('display: inline;', $view);
        $this->assertStringContainsString('@media (max-width: 575.98px)', $view);
        $this->assertStringContainsString('display: block;', $view);
        $this->assertStringContainsString('line-height: 1;', $view);
        $this->assertStringContainsString(
            '.sales-page-wrapper table#my-table.dataTable thead th.sorting::after',
            $view
        );
        $this->assertStringContainsString('display: none !important;', $view);
        $this->assertStringContainsString('content: none !important;', $view);
        $this->assertStringNotContainsString('color: rgba(255, 255, 255, 0.72);', $view);
        $this->assertStringNotContainsString('sales-header-label', $view);
    }
}
