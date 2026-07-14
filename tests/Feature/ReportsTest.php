<?php

namespace Tests\Feature;

use App\Build;
use App\client;
use App\Device;
use App\Http\Controllers\ReportsController;
use App\invoice;
use App\job;
use App\JobType;
use App\material;
use App\Modules\DeviceStageBatches\DeviceStageBatchService;
use App\sCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    use DatabaseTransactions;

    private const REPORT_FROM = '2026-06-01';
    private const REPORT_TO = '2026-06-30';
    private const REPORT_MONTH = '2026-06';

    private client $doctor;
    private material $zirconMaterial;
    private material $emaxMaterial;
    private JobType $jobType;
    private sCase $splitCase;
    private sCase $unsplitCase;
    private Device $firstDevice;
    private Device $secondDevice;
    private Collection $virtualRows;

    protected function setUp(): void
    {
        parent::setUp();

        $this->doctor = $this->createDoctor();
        $this->zirconMaterial = $this->ensureReportMaterial(1, 'Regression Zircon');
        $this->emaxMaterial = $this->ensureReportMaterial(2, 'Regression Emax');
        $this->jobType = $this->createJobType();
        $this->firstDevice = $this->createDevice('Split Mill A');
        $this->secondDevice = $this->createDevice('Split Mill B');

        $firstBuild = $this->createBuild($this->firstDevice, 'Split build A');
        $secondBuild = $this->createBuild($this->secondDevice, 'Split build B');

        $this->splitCase = $this->createCase('Split report case', null);
        $firstSplitJob = $this->createJob(
            $this->splitCase,
            $this->zirconMaterial,
            '11,12',
            2,
            $firstBuild
        );
        $secondSplitJob = $this->createJob(
            $this->splitCase,
            $this->emaxMaterial,
            '21,22,23',
            2,
            $secondBuild
        );

        $caseAtMilling = $this->splitCase->fresh(['jobs.material', 'jobs.jobType']);
        $this->virtualRows = (new DeviceStageBatchService())->rowsForStage(
            collect([$caseAtMilling]),
            2
        );

        job::whereIn('id', [$firstSplitJob->id, $secondSplitJob->id])->update([
            'stage' => -1,
            'is_set' => 1,
            'is_active' => 0,
        ]);
        $this->completeCase($this->splitCase, '2026-06-15 12:00:00');
        $this->createInvoice($this->splitCase, 500);

        $this->unsplitCase = $this->createCase('Unsplit report case', '2026-06-20 12:00:00');
        $this->createJob($this->unsplitCase, $this->zirconMaterial, '31', -1);
        $this->createInvoice($this->unsplitCase, 100);

        $outsideRangeCase = $this->createCase('Outside report range', '2026-05-20 12:00:00');
        $this->createJob($outsideRangeCase, $this->zirconMaterial, '41,42,43,44', -1);
        $this->createInvoice($outsideRangeCase, 900);
    }

    /** @test */
    public function master_report_counts_a_split_case_once_and_keeps_all_split_build_data(): void
    {
        $this->assertCount(2, $this->virtualRows);
        $this->assertSame(
            [$this->splitCase->id],
            $this->virtualRows->pluck('id')->unique()->values()->all()
        );
        $this->assertSame([2, 3], $this->virtualRows->map->unitsAmount()->sort()->values()->all());
        $this->assertCount(2, $this->virtualRows->map->selectionValue()->unique());

        $data = $this->reportData('masterReport', [
            'from' => self::REPORT_FROM,
            'to' => self::REPORT_TO,
            'doctor' => [$this->doctor->id],
        ]);

        $cases = $data['cases'];
        $this->assertSame(
            [$this->splitCase->id, $this->unsplitCase->id],
            $cases->pluck('id')->sort()->values()->all()
        );
        $this->assertCount(2, $cases);
        $this->assertSame(6, $this->countReportUnits($cases));
        $this->assertSame(600.0, (float) $cases->sum(fn (sCase $case) => optional($case->invoice)->amount ?? 0));

        $reportedSplitCase = $cases->firstWhere('id', $this->splitCase->id);
        $this->assertNotNull($reportedSplitCase);
        $this->assertCount(2, $reportedSplitCase->jobs);
        $this->assertCount(2, $reportedSplitCase->jobs->pluck('milling_build_id')->unique());
        $this->assertSame(
            collect([$this->firstDevice->name, $this->secondDevice->name])->sort()->values()->all(),
            collect(explode(', ', $reportedSplitCase->master_report_stage_devices[2]))->sort()->values()->all()
        );
    }

    /** @test */
    public function number_of_units_report_counts_each_real_unit_once_after_the_split(): void
    {
        $data = $this->reportData('numOfUnitsReport', [
            'from' => self::REPORT_FROM,
            'to' => self::REPORT_TO,
            'doctor' => [$this->doctor->id],
            'material' => [$this->zirconMaterial->id, $this->emaxMaterial->id],
        ]);

        $doctorTotals = $data['totals'][$this->doctor->id];
        $this->assertSame(3, $doctorTotals[$this->zirconMaterial->id]);
        $this->assertSame(3, $doctorTotals[$this->emaxMaterial->id]);
        $this->assertSame(6, array_sum($doctorTotals));
        $this->assertSame(
            [$this->doctor->id],
            collect($data['selectedClients'])->map(fn ($id) => (int) $id)->all()
        );
    }

    /** @test */
    public function job_types_report_counts_split_jobs_as_six_units_but_only_two_cases(): void
    {
        $unitsData = $this->reportData('jobTypeReport', [
            'from' => self::REPORT_FROM,
            'to' => self::REPORT_TO,
            'doctor' => [$this->doctor->id],
            'jobTypesInput' => [$this->jobType->id],
            'perToggle' => 1,
        ]);
        $casesData = $this->reportData('jobTypeReport', [
            'from' => self::REPORT_FROM,
            'to' => self::REPORT_TO,
            'doctor' => [$this->doctor->id],
            'jobTypesInput' => [$this->jobType->id],
            'perToggle' => 0,
        ]);

        $this->assertTrue($unitsData['perUnitTrigger']);
        $this->assertFalse($casesData['perUnitTrigger']);
        $this->assertSame([$this->jobType->id], $unitsData['selectedJobTypes']->pluck('id')->all());
        $this->assertSame(6, $this->doctor->numOfUnitsByJobType($this->jobType->id, self::REPORT_MONTH));
        $this->assertSame(2, $this->doctor->numOfCasesByJobType($this->jobType->id, self::REPORT_MONTH));
        $this->assertSame(
            [$this->splitCase->id, $this->unsplitCase->id],
            $this->doctor->caseIdsByJobTypes([$this->jobType->id], self::REPORT_MONTH)
                ->sort()
                ->values()
                ->all()
        );
    }

    /** @test */
    public function materials_report_counts_both_split_materials_and_each_invoice_once(): void
    {
        $data = $this->reportData('materialReport', [
            'from' => self::REPORT_FROM,
            'to' => self::REPORT_TO,
            'doctor' => [$this->doctor->id],
        ]);

        $cases = $data['cases'];
        $this->assertSame(
            [$this->splitCase->id, $this->unsplitCase->id],
            $cases->pluck('id')->sort()->values()->all()
        );
        $this->assertSame(600.0, (float) $data['totalAmount']);
        $this->assertSame(3, $cases->sum(fn (sCase $case) => $case->materialUsed([1, 20])));
        $this->assertSame(3, $cases->sum(fn (sCase $case) => $case->materialUsed([2])));
        $this->assertSame(0, $cases->sum(fn (sCase $case) => $case->materialUsed([3, 4, 6, 7])));
        $this->assertSame(0, $cases->sum(fn (sCase $case) => $case->materialUsed([9, 10])));
    }

    private function reportData(string $method, array $query): array
    {
        $request = Request::create('/reports/test', 'GET', $query);
        $view = app(ReportsController::class)->{$method}($request);

        return $view->getData();
    }

    private function createDoctor(): client
    {
        $suffix = uniqid('', true);
        $doctor = new client();
        $doctor->name = 'Reports Regression Doctor ' . $suffix;
        $doctor->phone = '0790000000';
        $doctor->address = 'Local test address';
        $doctor->active = 1;
        $doctor->save();

        return $doctor;
    }

    private function ensureReportMaterial(int $id, string $name): material
    {
        $material = material::withTrashed()->find($id) ?? new material();
        $material->id = $id;
        $material->name = $name;
        $material->price = 100;
        $material->design = 1;
        $material->mill = 1;
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
        $material->deleted_at = null;
        $material->save();

        return $material;
    }

    private function createJobType(): JobType
    {
        $jobType = new JobType();
        $jobType->name = 'Split Report Type ' . uniqid('', true);
        $jobType->teeth_or_jaw = 0;
        $jobType->a_secondary_item = 0;
        $jobType->save();

        return $jobType;
    }

    private function createDevice(string $name): Device
    {
        $device = new Device();
        $device->name = $name . ' ' . uniqid('', true);
        $device->type = 2;
        $device->sorting_order = 0;
        $device->hidden = 0;
        $device->is_dry = 1;
        $device->is_wet = 1;
        $device->save();

        return $device;
    }

    private function createBuild(Device $device, string $name): Build
    {
        $build = new Build();
        $build->name = $name . ' ' . uniqid('', true);
        $build->device_used = (string) $device->id;
        $build->type = 2;
        $build->set_at = '2026-06-14 12:00:00';
        $build->save();

        return $build;
    }

    private function createCase(string $patientName, ?string $actualDeliveryDate): sCase
    {
        return sCase::create([
            'case_id' => 'REPORT-' . uniqid('', true),
            'patient_name' => $patientName,
            'initial_delivery_date' => '2026-06-15 12:00:00',
            'actual_delivery_date' => $actualDeliveryDate,
            'delivered_to_client' => $actualDeliveryDate === null ? 0 : 1,
            'doctor_id' => $this->doctor->id,
        ]);
    }

    private function completeCase(sCase $case, string $actualDeliveryDate): void
    {
        $case->actual_delivery_date = $actualDeliveryDate;
        $case->delivered_to_client = 1;
        $case->save();
    }

    private function createJob(
        sCase $case,
        material $material,
        string $units,
        int $stage,
        ?Build $build = null
    ): job {
        return job::create([
            'unit_num' => $units,
            'type' => $this->jobType->id,
            'color' => 'A2',
            'style' => '0',
            'material_id' => $material->id,
            'case_id' => $case->id,
            'doctor_id' => $this->doctor->id,
            'stage' => $stage,
            'unit_price' => 100,
            'is_rejection' => 0,
            'has_been_rejected' => 0,
            'is_repeat' => 0,
            'is_modification' => 0,
            'is_redo' => 0,
            'milling_build_id' => optional($build)->id,
        ]);
    }

    private function createInvoice(sCase $case, float $amount): invoice
    {
        $invoice = new invoice();
        $invoice->status = 1;
        $invoice->amount = $amount;
        $invoice->amount_before_discount = $amount;
        $invoice->case_id = $case->id;
        $invoice->doctor_id = $this->doctor->id;
        $invoice->date_applied = $case->actual_delivery_date;
        $invoice->rejection_invoice = 0;
        $invoice->save();

        return $invoice;
    }

    private function countReportUnits(Collection $cases): int
    {
        return $cases->sum(function (sCase $case) {
            return $case->jobs->sum(function (job $job) {
                $units = array_filter(preg_split('/[,\s]+/', trim((string) $job->unit_num)));

                return max(1, count($units));
            });
        });
    }
}
