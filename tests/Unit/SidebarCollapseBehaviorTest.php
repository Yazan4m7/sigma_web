<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SidebarCollapseBehaviorTest extends TestCase
{
    /** @test */
    public function mobile_submenu_toggles_ignore_the_desktop_icons_only_preference(): void
    {
        $script = file_get_contents(__DIR__ . '/../../public/js/sidebar-collapse.js');

        $this->assertStringContainsString(
            'const isSidebarExpansionDisabled = () => !isMobile() && getDisableExpandPreference();',
            $script
        );
        $this->assertStringContainsString('if (isSubmenuToggle) {', $script);
        $this->assertStringContainsString('if (isSidebarExpansionDisabled()) {', $script);
        $this->assertStringContainsString('toggleSubmenu(link, targetId);', $script);
    }
}
