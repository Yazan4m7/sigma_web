<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class OperationsDashboardMobileTabsTest extends TestCase
{
    /** @test */
    public function mobile_inner_tabs_split_active_and_waiting_into_equal_halves(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/cases/admin-dashboardv2.blade.php');
        $css = file_get_contents(__DIR__ . '/../../public/assets/css/ysh-custom-css/OperationsDashboardStyling.css');
        $tabs = file_get_contents(__DIR__ . '/../../resources/views/cases/dashboards-partials/tabs.blade.php');

        $this->assertStringContainsString('class="stage-inner-tabs"', $tabs);
        $this->assertStringContainsString('class="innerActiveBtn innerBtn"', $tabs);
        $this->assertStringContainsString('class="innerWaitingBtn innerBtn"', $tabs);
        $this->assertStringContainsString('@media (max-width: 767.98px)', $view);
        $this->assertStringContainsString(
            '.ops-dashboard .macaw-tabs.macaw-silk-tabs .stage-inner-tabs .innerBtn',
            $view
        );
        $this->assertStringContainsString('flex-wrap: nowrap;', $view);
        $this->assertStringContainsString('gap: 0;', $view);
        $this->assertStringContainsString('flex: 0 0 50% !important;', $view);
        $this->assertStringContainsString('width: 50% !important;', $view);
        $this->assertStringContainsString('max-width: 50% !important;', $view);
        $this->assertStringContainsString('@media only screen and (max-width: 500px)', $css);

        $desktopTablistRule = $this->cssRule(
            $css,
            '.macaw-tabs.macaw-silk-tabs > [role="tablist"]'
        );
        $mobileTablistRule = $this->cssRule(
            $css,
            '.ops-dashboard .macaw-tabs.macaw-silk-tabs > [role="tablist"].stage-inner-tabs'
        );
        $mobileButtonRule = $this->cssRule(
            $css,
            '.ops-dashboard .macaw-tabs.macaw-silk-tabs > [role="tablist"].stage-inner-tabs > [role="tab"]'
        );

        $this->assertStringContainsString('align-items: center;', $desktopTablistRule);
        $this->assertStringContainsString('justify-content: flex-start;', $desktopTablistRule);
        $this->assertStringContainsString('justify-content: center;', $mobileTablistRule);
        $this->assertStringNotContainsString('justify-content: space-around;', $mobileTablistRule);
        $this->assertStringContainsString('padding: 1% 13% !important;', $css);
        $this->assertStringContainsString('padding: 1% 13% !important;', $mobileButtonRule);
    }

    private function cssRule(string $css, string $selector): string
    {
        $start = strpos($css, $selector . ' {');

        $this->assertNotFalse($start, "Missing CSS selector: {$selector}");

        $end = strpos($css, '}', $start);

        $this->assertNotFalse($end, "Missing CSS rule close for: {$selector}");

        return substr($css, $start, $end - $start + 1);
    }
}
