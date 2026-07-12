<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class WaitingDeliveryDialogTest extends TestCase
{
    public function test_dialog_uses_consistent_assign_state_and_backdrop_dismissal(): void
    {
        $component = file_get_contents(resource_path('views/components/waiting-delivery-dialog.blade.php'));
        $dashboard = file_get_contents(resource_path('views/cases/admin-dashboardv2.blade.php'));
        $scripts = file_get_contents(public_path('assets/js/ysh-custom-js/v3scripts.js'));
        $dashboardScripts = file_get_contents(public_path('assets/js/ysh-custom-js/operationsDashboardJS.js'));

        $this->assertStringContainsString('onclick="dismissDeliveryDialog(event)"', $component);
        $this->assertStringContainsString('event.target === event.currentTarget', $component);
        $this->assertStringContainsString('btnText="Assign"', $dashboard);
        $this->assertStringContainsString("assignButton.innerText = 'Assign';", $scripts);
        $this->assertStringNotContainsString("assignButton.innerText = 'ASSIGN';", $scripts);
        $this->assertStringNotContainsString("submitButton.innerText = 'Processing...';", $dashboardScripts);
        $this->assertStringContainsString('deliveryDialogFadeIn', $component);
    }

    public function test_user_photo_is_preferred_over_stored_default_placeholder(): void
    {
        $userId = 999999;
        $avatarPath = public_path("assets/images/avatars/user_{$userId}.png");
        file_put_contents($avatarPath, 'avatar-test');

        try {
            $user = new User([
                'img' => 'assets/images/avatars/default.png',
                'has_photo' => 1,
            ]);
            $user->id = $userId;

            $this->assertSame("assets/images/avatars/user_{$userId}.png", $user->avatar_path);
        } finally {
            @unlink($avatarPath);
        }
    }
}
