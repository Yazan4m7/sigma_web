<?php

namespace App\Console\Commands;

use App\AuditLog;
use App\caseLog;
use App\client;
use App\invoice;
use App\job;
use App\sCase;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class RepairDeliveryInvoiceApplications extends Command
{
    private const DELIVERY_TAKE_STAGE = 8.2;
    private const DELIVERY_COMPLETE_STAGE = 8.3;
    private const STAGE_EPSILON = 0.001;
    private const MONEY_EPSILON = 0.00001;

    private const INCIDENT_CASE_AMOUNTS = [
        21652 => 150.0,
        21707 => 50.0,
        21709 => 50.0,
        21712 => 100.0,
        21722 => 100.0,
        21723 => 20.0,
        21726 => 60.0,
        21727 => 50.0,
        21728 => 100.0,
        21729 => 50.0,
        21731 => 50.0,
        21739 => 50.0,
        21744 => 60.0,
        21745 => 60.0,
    ];

    protected $signature = 'invoices:repair-delivery-incident
                            {--case=* : Limit the run to one or more known incident case IDs. Defaults to all 14 cases.}
                            {--dry-run : Explicitly validate without writing. This is the default mode.}
                            {--commit : Apply the validated invoice, balance, history, and audit changes.}
                            {--run-id= : Optional audit run identifier. A UUID is generated when omitted.}';

    protected $description = 'Safely repair invoice applications interrupted during delivery completion.';

    public function handle(): int
    {
        if ($this->option('commit') && $this->option('dry-run')) {
            $this->error('Choose either --commit or --dry-run, not both. Dry-run is already the default.');
            return 1;
        }

        $commit = (bool) $this->option('commit');
        $runId = trim((string) $this->option('run-id')) ?: (string) Str::uuid();

        try {
            $caseIds = $this->selectedCaseIds();

            $result = DB::transaction(function () use ($caseIds, $commit, $runId): array {
                $batch = $this->validateBatchLocked($caseIds);

                if (!$commit) {
                    return $this->summarizeBatch($batch, false, 0, 0.0, 0);
                }

                return $this->applyValidatedBatch($batch, $runId);
            });
        } catch (Throwable $exception) {
            $this->error('Repair aborted with no committed changes: ' . $exception->getMessage());
            return 1;
        }

        $this->renderSummary($result, $runId, $commit);

        return 0;
    }

    private function selectedCaseIds(): array
    {
        $requestedValues = (array) $this->option('case');
        $tokens = [];

        foreach ($requestedValues as $requestedValue) {
            foreach (preg_split('/[\s,]+/', trim((string) $requestedValue), -1, PREG_SPLIT_NO_EMPTY) ?: [] as $token) {
                $tokens[] = $token;
            }
        }

        if ($tokens === []) {
            return array_keys(self::INCIDENT_CASE_AMOUNTS);
        }

        $caseIds = [];
        foreach ($tokens as $token) {
            if (!ctype_digit($token) || (int) $token < 1) {
                throw new RuntimeException("Invalid case ID [{$token}].");
            }

            $caseIds[] = (int) $token;
        }

        $caseIds = array_values(array_unique($caseIds));
        sort($caseIds, SORT_NUMERIC);

        $unknownCaseIds = array_values(array_diff($caseIds, array_keys(self::INCIDENT_CASE_AMOUNTS)));
        if ($unknownCaseIds !== []) {
            throw new RuntimeException(
                'This incident command only accepts the verified case IDs. Unknown IDs: ' . implode(', ', $unknownCaseIds)
            );
        }

        return $caseIds;
    }

    private function validateBatchLocked(array $caseIds): array
    {
        $cases = sCase::withTrashed()
            ->whereIn('id', $caseIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        $this->assertAllRowsExist('cases', $caseIds, $cases->keys()->all());

        $jobsByCase = job::withTrashed()
            ->whereIn('case_id', $caseIds)
            ->orderBy('case_id')
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->groupBy('case_id');

        $invoicesByCase = invoice::withTrashed()
            ->whereIn('case_id', $caseIds)
            ->orderBy('case_id')
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->groupBy('case_id');

        $logsByCase = caseLog::withTrashed()
            ->whereIn('case_id', $caseIds)
            ->whereBetween('stage', [
                self::DELIVERY_TAKE_STAGE - self::STAGE_EPSILON,
                self::DELIVERY_COMPLETE_STAGE + self::STAGE_EPSILON,
            ])
            ->orderBy('case_id')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->lockForUpdate()
            ->get()
            ->groupBy('case_id');

        $doctorIds = $cases->pluck('doctor_id')
            ->map(static fn ($doctorId): int => (int) $doctorId)
            ->unique()
            ->sort()
            ->values()
            ->all();

        $clients = client::withTrashed()
            ->whereIn('id', $doctorIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        $this->assertAllRowsExist('clients', $doctorIds, $clients->keys()->all());

        $plans = [];
        foreach ($caseIds as $caseId) {
            $case = $cases->get($caseId);
            $plans[] = $this->validateCaseState(
                $caseId,
                $case,
                $clients->get((int) $case->doctor_id),
                $jobsByCase->get($caseId, collect()),
                $invoicesByCase->get($caseId, collect()),
                $logsByCase->get($caseId, collect())
            );
        }

        $deliveryUserIds = collect($plans)
            ->pluck('delivery_user_id')
            ->unique()
            ->sort()
            ->values()
            ->all();

        $deliveryUsers = User::withTrashed()
            ->whereIn('id', $deliveryUserIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        $this->assertAllRowsExist('delivery users', $deliveryUserIds, $deliveryUsers->keys()->all());

        $actualInvoiceTotal = array_sum(array_column($plans, 'amount'));
        $expectedInvoiceTotal = array_sum(array_map(
            static fn (int $caseId): float => self::INCIDENT_CASE_AMOUNTS[$caseId],
            $caseIds
        ));

        if (!$this->moneyEquals($actualInvoiceTotal, $expectedInvoiceTotal)) {
            throw new RuntimeException(sprintf(
                'Invoice total mismatch: expected %.2f JOD, found %.2f JOD.',
                $expectedInvoiceTotal,
                $actualInvoiceTotal
            ));
        }

        return [
            'case_ids' => $caseIds,
            'plans' => $plans,
            'clients' => $clients,
            'invoice_total' => $actualInvoiceTotal,
        ];
    }

    private function validateCaseState(
        int $caseId,
        sCase $case,
        client $client,
        Collection $allJobs,
        Collection $allInvoices,
        Collection $deliveryLogs
    ): array {
        if ($case->trashed()) {
            throw new RuntimeException("Case {$caseId} is soft-deleted.");
        }

        if ($client->trashed()) {
            throw new RuntimeException("Case {$caseId} belongs to a soft-deleted client.");
        }

        if ((int) $case->delivered_to_client !== 1) {
            throw new RuntimeException("Case {$caseId} is not marked delivered_to_client.");
        }

        if ((int) $case->contains_modification !== 0) {
            throw new RuntimeException("Case {$caseId} is a modification case and must not be financially repaired here.");
        }

        if ((int) $case->notification_sent !== 1) {
            throw new RuntimeException("Case {$caseId} does not match the incident fingerprint notification_sent=1.");
        }

        $actualDeliveryAt = $this->normalizeRequiredTimestamp(
            $case->getRawOriginal('actual_delivery_date'),
            "Case {$caseId} has no valid actual_delivery_date."
        );

        $activeJobs = $allJobs
            ->reject(static fn (job $job): bool => $job->trashed())
            ->values();

        if ($activeJobs->isEmpty()) {
            throw new RuntimeException("Case {$caseId} has no active jobs.");
        }

        if ($activeJobs->contains(static fn (job $job): bool => (float) $job->stage !== -1.0)) {
            throw new RuntimeException("Case {$caseId} still has an active job outside the completed stage.");
        }

        if ($activeJobs->contains(static function (job $job): bool {
            return $job->delivery_accepted === null || $job->delivery_accepted === '';
        })) {
            throw new RuntimeException("Case {$caseId} has a completed job without a delivery_accepted user.");
        }

        $deliveryUserIds = $activeJobs->pluck('delivery_accepted')
            ->map(static fn ($userId): int => (int) $userId)
            ->unique()
            ->values();

        if ($deliveryUserIds->count() !== 1) {
            throw new RuntimeException("Case {$caseId} has conflicting delivery_accepted users across its active jobs.");
        }

        $deliveryUserId = (int) $deliveryUserIds->first();

        if ($allInvoices->count() !== 1) {
            throw new RuntimeException(
                "Case {$caseId} must have exactly one invoice including soft-deleted history; found {$allInvoices->count()}."
            );
        }

        /** @var invoice $invoice */
        $invoice = $allInvoices->first();
        if ($invoice->trashed()) {
            throw new RuntimeException("Case {$caseId}'s only invoice is soft-deleted.");
        }

        if ((int) $invoice->doctor_id !== (int) $case->doctor_id || (int) $client->id !== (int) $case->doctor_id) {
            throw new RuntimeException("Case {$caseId} has mismatched case, invoice, or client doctor IDs.");
        }

        $amount = (float) $invoice->amount;
        $expectedAmount = self::INCIDENT_CASE_AMOUNTS[$caseId];
        if ($amount < 0 || !$this->moneyEquals($amount, $expectedAmount)) {
            throw new RuntimeException(sprintf(
                'Case %d invoice amount mismatch: expected %.2f JOD, found %.2f JOD.',
                $caseId,
                $expectedAmount,
                $amount
            ));
        }

        $takeLog = $deliveryLogs
            ->filter(function (caseLog $log): bool {
                return !$log->trashed() && $this->stageEquals($log->stage, self::DELIVERY_TAKE_STAGE);
            })
            ->first();

        if (!$takeLog) {
            throw new RuntimeException("Case {$caseId} has no active Delivery Take (8.2) log.");
        }

        if ((int) $takeLog->user_id !== $deliveryUserId) {
            throw new RuntimeException("Case {$caseId}'s Delivery Take user does not match jobs.delivery_accepted.");
        }

        $takeAt = $this->normalizeRequiredTimestamp(
            $takeLog->getRawOriginal('created_at'),
            "Case {$caseId}'s Delivery Take log has no valid timestamp."
        );

        if (Carbon::parse($takeAt)->gt(Carbon::parse($actualDeliveryAt))) {
            throw new RuntimeException("Case {$caseId}'s Delivery Take log occurs after actual_delivery_date.");
        }

        $completionLogs = $deliveryLogs
            ->filter(fn (caseLog $log): bool => $this->stageEquals($log->stage, self::DELIVERY_COMPLETE_STAGE))
            ->values();
        $activeCompletionLogs = $completionLogs
            ->reject(static fn (caseLog $log): bool => $log->trashed())
            ->values();

        $invoiceAppliedAt = $this->normalizeOptionalTimestamp($invoice->getRawOriginal('date_applied'));
        $isPendingInvoice = (int) $invoice->status === 0 && $invoiceAppliedAt === null;
        $isAppliedInvoice = (int) $invoice->status === 1 && $invoiceAppliedAt !== null;

        if ($isPendingInvoice && $completionLogs->isEmpty()) {
            $state = 'pending';
        } elseif ($isAppliedInvoice && $completionLogs->count() === 1 && $activeCompletionLogs->count() === 1) {
            /** @var caseLog $completionLog */
            $completionLog = $activeCompletionLogs->first();
            $completionAt = $this->normalizeRequiredTimestamp(
                $completionLog->getRawOriginal('created_at'),
                "Case {$caseId}'s Delivery Complete log has no valid timestamp."
            );
            $completionAttributes = $completionLog->getAttributes();
            $validOptionalMetadata = (
                !array_key_exists('action_type', $completionAttributes)
                || (int) $completionAttributes['action_type'] === 3
            ) && (
                !array_key_exists('device_id', $completionAttributes)
                || $completionAttributes['device_id'] === null
            );

            $validCompletion = $invoiceAppliedAt === $actualDeliveryAt
                && $completionAt === $actualDeliveryAt
                && (int) $completionLog->user_id === $deliveryUserId
                && (int) $completionLog->is_completion === 1
                && $validOptionalMetadata;

            if (!$validCompletion) {
                throw new RuntimeException("Case {$caseId} has an applied invoice with a mismatched Delivery Complete log.");
            }

            $state = 'repaired';
        } else {
            throw new RuntimeException(
                "Case {$caseId} has a mixed or unsafe invoice/log state (status {$invoice->status}, "
                . 'date_applied ' . ($invoiceAppliedAt ?? 'NULL')
                . ", completion logs {$completionLogs->count()})."
            );
        }

        return [
            'case_id' => $caseId,
            'case_number' => (string) $case->case_id,
            'case' => $case,
            'invoice' => $invoice,
            'client' => $client,
            'invoice_id' => (int) $invoice->id,
            'doctor_id' => (int) $case->doctor_id,
            'delivery_user_id' => $deliveryUserId,
            'take_log_id' => (int) $takeLog->id,
            'actual_delivery_at' => $actualDeliveryAt,
            'amount' => $amount,
            'notification_sent' => (int) $case->notification_sent,
            'state' => $state,
        ];
    }

    private function applyValidatedBatch(array $batch, string $runId): array
    {
        $pendingPlans = array_values(array_filter(
            $batch['plans'],
            static fn (array $plan): bool => $plan['state'] === 'pending'
        ));

        if ($pendingPlans === []) {
            return $this->summarizeBatch($batch, true, 0, 0.0, 0);
        }

        foreach ($pendingPlans as $plan) {
            /** @var invoice $invoice */
            $invoice = $plan['invoice'];
            $invoice->status = 1;
            $invoice->date_applied = $plan['actual_delivery_at'];
            $invoice->save();
        }

        $clientDeltas = [];
        foreach ($pendingPlans as $plan) {
            $doctorId = $plan['doctor_id'];
            $clientDeltas[$doctorId] = ($clientDeltas[$doctorId] ?? 0.0) + $plan['amount'];
        }
        ksort($clientDeltas, SORT_NUMERIC);

        $clientBalanceSnapshots = [];
        foreach ($clientDeltas as $doctorId => $delta) {
            /** @var client $client */
            $client = $batch['clients']->get($doctorId);
            $balanceBefore = (float) $client->balance;
            $balanceAfter = $balanceBefore + $delta;

            $client->balance = $balanceAfter;
            $client->save();

            $clientBalanceSnapshots[$doctorId] = [
                'before' => $balanceBefore,
                'after' => $balanceAfter,
                'delta' => $delta,
            ];
        }

        $completionLogIds = [];
        foreach ($pendingPlans as $plan) {
            $completionLog = new caseLog([
                'user_id' => $plan['delivery_user_id'],
                'case_id' => $plan['case_id'],
                'stage' => self::DELIVERY_COMPLETE_STAGE,
                'device_id' => null,
                'action_type' => 3,
                'is_completion' => 1,
            ]);
            $completionLog->created_at = $plan['actual_delivery_at'];
            $completionLog->updated_at = $plan['actual_delivery_at'];
            $completionLog->save();

            $completionLogIds[$plan['case_id']] = (int) $completionLog->id;
        }

        foreach ($pendingPlans as $plan) {
            $balanceSnapshot = $clientBalanceSnapshots[$plan['doctor_id']];

            AuditLog::create([
                'user_id' => null,
                'action' => 'repair_delivery_invoice_application',
                'description' => "Repaired interrupted delivery accounting for case #{$plan['case_id']}.",
                'subject_type' => sCase::class,
                'subject_id' => $plan['case_id'],
                'properties' => [
                    'run_id' => $runId,
                    'case_number' => $plan['case_number'],
                    'invoice_id' => $plan['invoice_id'],
                    'amount' => $plan['amount'],
                    'doctor_id' => $plan['doctor_id'],
                    'delivery_user_id' => $plan['delivery_user_id'],
                    'take_log_id' => $plan['take_log_id'],
                    'completion_log_id' => $completionLogIds[$plan['case_id']],
                    'date_applied' => $plan['actual_delivery_at'],
                    'notification_sent_before' => $plan['notification_sent'],
                    'notification_sent_after' => $plan['notification_sent'],
                    'client_balance_before_batch' => $balanceSnapshot['before'],
                    'client_balance_after_batch' => $balanceSnapshot['after'],
                    'client_batch_delta' => $balanceSnapshot['delta'],
                ],
                'ip_address' => null,
                'user_agent' => 'artisan:' . $this->getName(),
            ]);
        }

        return $this->summarizeBatch(
            $batch,
            true,
            count($pendingPlans),
            array_sum(array_column($pendingPlans, 'amount')),
            count($completionLogIds)
        );
    }

    private function summarizeBatch(
        array $batch,
        bool $commitMode,
        int $appliedCount,
        float $appliedAmount,
        int $insertedLogCount
    ): array {
        $pendingPlans = array_values(array_filter(
            $batch['plans'],
            static fn (array $plan): bool => $plan['state'] === 'pending'
        ));
        $repairedPlans = array_values(array_filter(
            $batch['plans'],
            static fn (array $plan): bool => $plan['state'] === 'repaired'
        ));

        return [
            'mode' => $commitMode ? 'commit' : 'dry-run',
            'case_count' => count($batch['plans']),
            'invoice_total' => (float) $batch['invoice_total'],
            'pending_count' => count($pendingPlans),
            'pending_amount' => array_sum(array_column($pendingPlans, 'amount')),
            'already_repaired_count' => count($repairedPlans),
            'applied_count' => $appliedCount,
            'applied_amount' => $appliedAmount,
            'inserted_log_count' => $insertedLogCount,
            'cases' => array_map(static function (array $plan): array {
                return [
                    'case_id' => $plan['case_id'],
                    'state' => $plan['state'],
                    'invoice_id' => $plan['invoice_id'],
                    'amount' => $plan['amount'],
                    'delivery_user_id' => $plan['delivery_user_id'],
                    'actual_delivery_at' => $plan['actual_delivery_at'],
                ];
            }, $batch['plans']),
        ];
    }

    private function renderSummary(array $result, string $runId, bool $commit): void
    {
        $this->table(
            ['Metric', 'Value'],
            [
                ['Mode', strtoupper($result['mode'])],
                ['Run ID', $runId],
                ['Validated cases', $result['case_count']],
                ['Verified invoice total', number_format($result['invoice_total'], 2) . ' JOD'],
                ['Pending cases', $result['pending_count']],
                ['Pending amount', number_format($result['pending_amount'], 2) . ' JOD'],
                ['Already repaired', $result['already_repaired_count']],
                ['Invoices applied now', $result['applied_count']],
                ['Amount applied now', number_format($result['applied_amount'], 2) . ' JOD'],
                ['Completion logs inserted', $result['inserted_log_count']],
            ]
        );

        $this->table(
            ['Case', 'State', 'Invoice', 'Amount', 'Delivery user', 'Delivery timestamp'],
            array_map(static function (array $row): array {
                return [
                    $row['case_id'],
                    strtoupper($row['state']),
                    $row['invoice_id'],
                    number_format($row['amount'], 2),
                    $row['delivery_user_id'],
                    $row['actual_delivery_at'],
                ];
            }, $result['cases'])
        );

        if (!$commit) {
            $this->warn('Dry-run only: no invoices, balances, history logs, or audit logs were changed.');
            $this->line('After reviewing this output, rerun the same selection with --commit.');
            return;
        }

        if ($result['applied_count'] === 0) {
            $this->info('All selected cases were already fully repaired; the idempotent commit made no changes.');
            return;
        }

        $this->info('The validated repair batch committed successfully.');
    }

    private function assertAllRowsExist(string $label, array $expectedIds, array $foundIds): void
    {
        $expectedIds = array_map('intval', $expectedIds);
        $foundIds = array_map('intval', $foundIds);
        sort($expectedIds, SORT_NUMERIC);
        sort($foundIds, SORT_NUMERIC);

        if ($expectedIds !== $foundIds) {
            $missingIds = array_values(array_diff($expectedIds, $foundIds));
            throw new RuntimeException("Missing {$label}: " . implode(', ', $missingIds));
        }
    }

    private function normalizeRequiredTimestamp($value, string $errorMessage): string
    {
        $normalized = $this->normalizeOptionalTimestamp($value);
        if ($normalized === null) {
            throw new RuntimeException($errorMessage);
        }

        return $normalized;
    }

    private function normalizeOptionalTimestamp($value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        try {
            return Carbon::parse((string) $value)->format('Y-m-d H:i:s');
        } catch (Throwable $exception) {
            return null;
        }
    }

    private function stageEquals($actual, float $expected): bool
    {
        return abs((float) $actual - $expected) < self::STAGE_EPSILON;
    }

    private function moneyEquals(float $actual, float $expected): bool
    {
        return abs($actual - $expected) < self::MONEY_EPSILON;
    }
}
