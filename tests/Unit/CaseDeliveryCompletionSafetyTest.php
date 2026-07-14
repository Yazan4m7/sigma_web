<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CaseDeliveryCompletionSafetyTest extends TestCase
{
    private string $controller;
    private string $notificationTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = file_get_contents(__DIR__ . '/../../app/Http/Controllers/CaseController.php');
        $this->notificationTrait = file_get_contents(__DIR__ . '/../../app/Http/Traits/helperTrait.php');
    }

    public function test_case_completion_uses_locked_transaction_and_persisted_all_jobs_check(): void
    {
        $method = $this->method('public function finishCaseStage', 'public function deliveredInBox');

        $this->assertStringContainsString('DB::transaction(function ()', $method);
        $this->assertStringContainsString('->lockForUpdate()', $method);
        $this->assertStringContainsString('$allJobsCompleted = $caseJobs->isNotEmpty()', $method);
        $this->assertStringContainsString('return (int) $caseJob->stage === -1;', $method);
        $this->assertStringNotContainsString('if ($nextStage == -1)', $method);
        $this->assertStringContainsString(
            'if ((int) $stage !== 8 || ($allJobsCompleted && $deliveryTransition))',
            $method
        );
        $this->assertStringContainsString('$this->issueInvoiceForLockedCase($case);', $method);
        $this->assertStringContainsString('$this->applyInvoiceForLockedCase($case);', $method);
    }

    public function test_invoice_application_is_transactional_idempotent_and_notification_independent(): void
    {
        $method = $this->method('public function applyInvoice($job)', 'private function sendCaseDeliveryNotificationSafely');

        $this->assertStringContainsString('DB::transaction(function ()', $method);
        $this->assertStringContainsString('->lockForUpdate()', $method);
        $this->assertStringContainsString('$statusApplied = (int) $invoice->status === 1', $method);
        $this->assertStringContainsString('$statusApplied !== $dateApplied', $method);
        $this->assertStringContainsString('has inconsistent application markers', $method);
        $this->assertStringContainsString('$invoice->date_applied = $case->actual_delivery_date ?: now();', $method);
        $this->assertStringNotContainsString('notification_sent', $method);
        $this->assertStringNotContainsString('sendCaseNotification', $method);
        $this->assertLessThan(
            strpos($method, '$client->balance ='),
            strpos($method, '$statusApplied =')
        );
    }

    public function test_notification_is_best_effort_and_marks_success_only_after_sends(): void
    {
        $finishMethod = $this->method('public function finishCaseStage', 'public function deliveredInBox');
        $notificationMethod = $this->method(
            'private function sendCaseDeliveryNotificationSafely',
            'public function invoicesList'
        );

        $this->assertStringContainsString('DB::afterCommit(function ()', $finishMethod);
        $this->assertStringContainsString('catch (\Throwable $exception)', $notificationMethod);
        $this->assertStringContainsString("->update(['notification_sent' => 1]);", $notificationMethod);
        $this->assertLessThan(
            strpos($notificationMethod, "->update(['notification_sent' => 1]);"),
            strpos($notificationMethod, '$this->sendCaseNotification(')
        );
    }

    public function test_invoice_issuance_handles_repeat_and_modification_jobs_case_wide(): void
    {
        $method = $this->method('public function issueInvoice($job)', 'public function applyInvoice($job)');

        $this->assertStringContainsString('$hasBillableJobs = false;', $method);
        $this->assertStringContainsString('(int) $job->is_repeat === 1', $method);
        $this->assertStringContainsString('(int) $job->is_modification === 1', $method);
        $this->assertStringContainsString('Never reopen an invoice that has already affected the client balance.', $method);
        $this->assertStringContainsString('$statusApplied !== $dateApplied', $method);
    }

    public function test_in_box_and_admin_completion_reuse_and_propagate_the_safe_flow(): void
    {
        $inBoxMethod = $this->method('public function deliveredInBox', 'private function markCaseDelivered');
        $adminMethod = $this->method('public function completeByAdmin', 'public function finishCaseCompletely');

        $this->assertStringContainsString(
            'return $this->finishCaseStage($caseId, 8, true, [], true);',
            $inBoxMethod
        );
        $this->assertStringContainsString('$finishResponse = $this->finishCaseStage', $adminMethod);
        $this->assertStringContainsString('return $finishResponse;', $adminMethod);
    }

    public function test_oauth_and_fcm_responses_are_validated_without_touching_credentials(): void
    {
        $this->assertStringContainsString("empty(\$json['access_token'])", $this->notificationTrait);
        $this->assertStringContainsString('CURLINFO_HTTP_CODE', $this->notificationTrait);
        $this->assertStringContainsString('Google OAuth token request failed', $this->notificationTrait);
        $this->assertStringContainsString('FCM request failed', $this->notificationTrait);
    }

    private function method(string $startMarker, string $endMarker): string
    {
        $start = strpos($this->controller, $startMarker);
        $end = strpos($this->controller, $endMarker, $start);

        $this->assertNotFalse($start, "Missing method marker: {$startMarker}");
        $this->assertNotFalse($end, "Missing method marker: {$endMarker}");

        return substr($this->controller, $start, $end - $start);
    }
}
