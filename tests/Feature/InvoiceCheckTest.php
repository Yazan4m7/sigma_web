<?php

namespace Tests\Feature;

use App\client;
use App\failureLog;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\ToolsController;
use App\invoice;
use App\job;
use App\JobType;
use App\material;
use App\sCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Tests\TestCase;

class InvoiceCheckTest extends TestCase
{
    use DatabaseTransactions;

    private client $doctor;
    private material $material;
    private JobType $jobType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->doctor = $this->createDoctor();
        $this->material = $this->createMaterial();
        $this->jobType = $this->createJobType();
    }

    /** @test */
    public function invoice_check_excludes_repeat_only_cases_but_keeps_real_missing_and_pending_invoices(): void
    {
        $repeatCase = $this->createCase('Repeat-only case', false);
        $this->createJob($repeatCase, 7, true, false, '11');
        $this->createInvoice($repeatCase, 0, '2026-07-10 10:00:00');

        $billableCase = $this->createCase('Billable missing invoice', false);
        $this->createJob($billableCase, 7, false, false, '12');

        $modifiedCase = $this->createCase('Pending original invoice', true);
        $this->createJob($modifiedCase, -1, false, false, '13,14');
        $this->createJob($modifiedCase, -1, false, true, '13,14');
        $this->createInvoice($modifiedCase, 100, '2026-07-10 10:00:00');

        $request = Request::create('/tools/invoice-check', 'GET', [
            'from' => '2026-07-01',
            'to' => '2026-07-31',
            'doctor' => [$this->doctor->id],
        ]);
        $cases = app(ToolsController::class)->invoiceCheck($request)->getData()['cases'];

        $this->assertNotContains($repeatCase->id, $cases->pluck('id')->all());
        $this->assertContains($billableCase->id, $cases->pluck('id')->all());
        $this->assertContains($modifiedCase->id, $cases->pluck('id')->all());
    }

    /** @test */
    public function issuing_an_invoice_for_a_repeat_only_case_does_not_create_a_zero_invoice(): void
    {
        $repeatCase = $this->createCase('Repeat-only without invoice', false);
        $this->createJob($repeatCase, 7, true, false, '11');

        $this->assertNull(app(CaseController::class)->issueInvoiceForCase($repeatCase->id));
        $this->assertFalse(invoice::where('case_id', $repeatCase->id)->exists());
    }

    /** @test */
    public function a_pending_original_invoice_can_be_applied_once_after_a_later_modification(): void
    {
        $case = $this->createCase('Modified after original delivery', true);
        $this->createJob($case, -1, false, false, '11,12,13,14,15,16,17,18');
        $this->createJob($case, -1, false, true, '11,12,13,14,15,16,17,18');
        $invoice = $this->createInvoice($case, 400, '2026-07-10 10:00:00');
        $this->createModificationLog($case, '2026-07-10 11:00:00');

        $controller = app(CaseController::class);
        $this->assertTrue($controller->applyInvoiceForCase($case->id));

        $invoice->refresh();
        $this->doctor->refresh();
        $this->assertSame(1, (int) $invoice->status);
        $this->assertSame('2026-07-10 09:00:00', $invoice->getRawOriginal('date_applied'));
        $this->assertSame(500.0, (float) $this->doctor->balance);

        $this->assertFalse($controller->applyInvoiceForCase($case->id));
        $this->doctor->refresh();
        $this->assertSame(500.0, (float) $this->doctor->balance);
    }

    /** @test */
    public function a_modified_case_cannot_apply_an_invoice_created_after_the_modification_started(): void
    {
        $case = $this->createCase('Unsafe modified invoice', true);
        $this->createJob($case, -1, false, false, '11');
        $this->createJob($case, -1, false, true, '11');
        $this->createModificationLog($case, '2026-07-10 10:00:00');
        $invoice = $this->createInvoice($case, 50, '2026-07-10 11:00:00');

        $this->assertFalse(app(CaseController::class)->applyInvoiceForCase($case->id));

        $invoice->refresh();
        $this->doctor->refresh();
        $this->assertSame(0, (int) $invoice->status);
        $this->assertNull($invoice->date_applied);
        $this->assertSame(100.0, (float) $this->doctor->balance);
    }

    private function createDoctor(): client
    {
        $doctor = new client();
        $doctor->name = 'Invoice Check Doctor ' . uniqid('', true);
        $doctor->phone = '0790000000';
        $doctor->address = 'Local test address';
        $doctor->balance = 100;
        $doctor->active = 1;
        $doctor->save();

        return $doctor;
    }

    private function createMaterial(): material
    {
        $material = new material();
        $material->name = 'Invoice Check Material ' . uniqid('', true);
        $material->price = 50;
        $material->design = 1;
        $material->mill = 0;
        $material->print_3d = 0;
        $material->sinter_furnace = 0;
        $material->press_furnace = 0;
        $material->finish = 1;
        $material->qc = 1;
        $material->delivery = 1;
        $material->restricted = 0;
        $material->count_as_unit = 1;
        $material->count_in_units_counts_report = 1;
        $material->count_in_job_types_report = 1;
        $material->count_in_qc_report = 1;
        $material->count_in_implants_report = 1;
        $material->is_active = 1;
        $material->default_type_id = 0;
        $material->save();

        return $material;
    }

    private function createJobType(): JobType
    {
        $jobType = new JobType();
        $jobType->name = 'Invoice Check Type ' . uniqid('', true);
        $jobType->teeth_or_jaw = 0;
        $jobType->a_secondary_item = 0;
        $jobType->save();

        return $jobType;
    }

    private function createCase(string $patientName, bool $containsModification): sCase
    {
        return sCase::create([
            'case_id' => 'INV-CHECK-' . uniqid('', true),
            'patient_name' => $patientName,
            'initial_delivery_date' => '2026-07-10 09:00:00',
            'actual_delivery_date' => $containsModification ? '2026-07-10 09:00:00' : null,
            'delivered_to_client' => $containsModification ? 1 : 0,
            'doctor_id' => $this->doctor->id,
            'contains_modification' => $containsModification ? 1 : 0,
            'created_at' => '2026-07-10 08:00:00',
        ]);
    }

    private function createJob(
        sCase $case,
        int $stage,
        bool $isRepeat,
        bool $isModification,
        string $units
    ): job {
        return job::create([
            'unit_num' => $units,
            'type' => $this->jobType->id,
            'color' => 'A2',
            'style' => '0',
            'material_id' => $this->material->id,
            'case_id' => $case->id,
            'doctor_id' => $this->doctor->id,
            'stage' => $stage,
            'unit_price' => 50,
            'is_rejection' => 0,
            'has_been_rejected' => 0,
            'is_repeat' => $isRepeat ? 1 : 0,
            'is_modification' => $isModification ? 1 : 0,
            'is_redo' => 0,
        ]);
    }

    private function createInvoice(sCase $case, float $amount, string $createdAt): invoice
    {
        $invoice = new invoice();
        $invoice->status = 0;
        $invoice->amount = $amount;
        $invoice->amount_before_discount = $amount;
        $invoice->case_id = $case->id;
        $invoice->doctor_id = $this->doctor->id;
        $invoice->rejection_invoice = 0;
        $invoice->created_at = $createdAt;
        $invoice->save();

        return $invoice;
    }

    private function createModificationLog(sCase $case, string $createdAt): failureLog
    {
        return failureLog::create([
            'case_id' => $case->id,
            'failure_type' => 2,
            'cause_id' => 1,
            'done_by' => 1,
            'old_delivery_date' => '2026-07-10 09:00:00',
            'created_at' => $createdAt,
        ]);
    }
}
