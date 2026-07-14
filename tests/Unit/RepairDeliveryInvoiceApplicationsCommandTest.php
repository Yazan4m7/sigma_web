<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class RepairDeliveryInvoiceApplicationsCommandTest extends TestCase
{
    public function test_delivery_invoice_incident_command_keeps_the_repair_atomic_idempotent_and_audited(): void
    {
        $command = file_get_contents(
            __DIR__ . '/../../app/Console/Commands/RepairDeliveryInvoiceApplications.php'
        );

        $this->assertIsString($command);
        $this->assertStringContainsString('invoices:repair-delivery-incident', $command);
        $this->assertStringContainsString('{--dry-run', $command);
        $this->assertStringContainsString('{--commit', $command);
        $this->assertStringContainsString('DB::transaction', $command);
        $this->assertStringContainsString('validateBatchLocked($caseIds)', $command);
        $this->assertStringContainsString('lockForUpdate()', $command);
        $this->assertStringContainsString('withTrashed()', $command);

        preg_match('/private const INCIDENT_CASE_AMOUNTS = \[(.*?)\];/s', $command, $amountBlock);
        $this->assertArrayHasKey(1, $amountBlock);

        preg_match_all('/^\s*(\d+)\s*=>\s*([0-9.]+),$/m', $amountBlock[1], $amountRows, PREG_SET_ORDER);
        $amounts = [];
        foreach ($amountRows as $row) {
            $amounts[(int) $row[1]] = (float) $row[2];
        }

        $this->assertSame(
            [21652, 21707, 21709, 21712, 21722, 21723, 21726, 21727, 21728, 21729, 21731, 21739, 21744, 21745],
            array_keys($amounts)
        );
        $this->assertCount(14, $amounts);
        $this->assertEqualsWithDelta(950.0, array_sum($amounts), 0.00001);

        $validationStart = strpos($command, 'private function validateBatchLocked');
        $applyStart = strpos($command, 'private function applyValidatedBatch');
        $this->assertNotFalse($validationStart);
        $this->assertNotFalse($applyStart);

        $validationCode = substr($command, $validationStart, $applyStart - $validationStart);
        $this->assertStringNotContainsString('->save()', $validationCode);
        $this->assertStringNotContainsString('AuditLog::create', $validationCode);
        $this->assertStringContainsString("\$state = 'pending'", $validationCode);
        $this->assertStringContainsString("\$state = 'repaired'", $validationCode);
        $this->assertStringContainsString('mixed or unsafe invoice/log state', $validationCode);
        $this->assertStringContainsString("array_key_exists('action_type', \$completionAttributes)", $validationCode);
        $this->assertStringContainsString("array_key_exists('device_id', \$completionAttributes)", $validationCode);

        $applyCode = substr($command, $applyStart);
        $this->assertStringContainsString("\$invoice->date_applied = \$plan['actual_delivery_at'];", $applyCode);
        $this->assertStringContainsString("'stage' => self::DELIVERY_COMPLETE_STAGE", $applyCode);
        $this->assertStringContainsString("'user_id' => \$plan['delivery_user_id']", $applyCode);
        $this->assertStringContainsString("'action_type' => 3", $applyCode);
        $this->assertStringContainsString("'is_completion' => 1", $applyCode);
        $this->assertStringContainsString('AuditLog::create', $applyCode);
        $this->assertStringContainsString("'run_id' => \$runId", $applyCode);
        $this->assertStringNotContainsString('$case->notification_sent =', $command);
    }
}
