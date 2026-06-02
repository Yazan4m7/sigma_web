<?php

namespace App\Console\Commands;

use App\Services\AuditLogger;
use App\sCase;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BackfillMillingBuildIds extends Command
{
    private ?string $backupFilePath = null;

    protected $signature = 'cases:backfill-milling-builds
                            {--apply : Persist the recovered milling_build_id values. Defaults to a dry run.}
                            {--case-id=* : Limit the run to one or more internal case IDs.}
                            {--limit=0 : Stop after processing this many candidate cases.}
                            {--from-set-at= : Only consider milling logs/builds on or after this timestamp. Defaults to the first build with a registered device.}
                            {--allow-one-second-drift : Allow a unique build match when set_at and/or finished_at differ by at most one second, while started_at matches exactly.}
                            {--sample=10 : Number of update/skip samples to print in the summary.}';

    protected $description = 'Backfill missing milling_build_id values from milling case logs and build timestamps.';

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');
        $allowOneSecondDrift = (bool) $this->option('allow-one-second-drift');
        $limit = max(0, (int) $this->option('limit'));
        $sampleSize = max(0, (int) $this->option('sample'));

        $fromSetAt = $this->option('from-set-at') ?: DB::table('builds')
            ->whereNotNull('device_used')
            ->min('set_at');

        if (empty($fromSetAt)) {
            $this->error('No builds with a registered device were found, so no safe recovery boundary is available.');
            return 1;
        }

        $candidateCases = $this->candidateCases($limit);

        if ($candidateCases->isEmpty()) {
            $this->warn('No candidate cases with mill-enabled jobs and missing milling_build_id values were found.');
            return 0;
        }

        $stats = [
            'mode' => $apply ? 'apply' : 'dry-run',
            'from_set_at' => $fromSetAt,
            'backup_file' => null,
            'candidate_cases' => $candidateCases->count(),
            'candidate_jobs' => 0,
            'updated_cases' => 0,
            'updated_jobs' => 0,
            'exact_match_cases' => 0,
            'exact_match_jobs' => 0,
            'drift_match_cases' => 0,
            'drift_match_jobs' => 0,
            'skipped_no_build_era_logs' => 0,
            'skipped_partial_or_duplicate_cycle' => 0,
            'skipped_no_unique_match' => 0,
            'skipped_concurrent_change' => 0,
        ];

        $updateSamples = [];
        $skipSamples = [];

        if ($apply) {
            $this->backupFilePath = $this->initializeBackupFile();
            $stats['backup_file'] = $this->backupFilePath;
        }

        $this->line(sprintf(
            'Running %s from build era boundary %s%s',
            $apply ? 'APPLY' : 'DRY RUN',
            $fromSetAt,
            $allowOneSecondDrift ? ' with one-second drift matching enabled' : ' with exact matching only'
        ));

        $bar = $this->output->createProgressBar($candidateCases->count());
        $bar->start();

        foreach ($candidateCases as $candidate) {
            $jobIds = $this->candidateMillJobIds((int) $candidate->case_id);

            if ($jobIds === []) {
                $bar->advance();
                continue;
            }

            $stats['candidate_jobs'] += count($jobIds);

            $millingCycle = $this->extractUniqueMillingCycle((int) $candidate->case_id, $fromSetAt);

            if (!$millingCycle['ok']) {
                if ($millingCycle['reason'] === 'no_build_era_logs') {
                    $stats['skipped_no_build_era_logs']++;
                } else {
                    $stats['skipped_partial_or_duplicate_cycle']++;
                }

                $this->pushSample($skipSamples, $sampleSize, [
                    'case_id' => $candidate->case_id,
                    'case_number' => $candidate->case_number,
                    'reason' => $millingCycle['reason'],
                    'job_count' => count($jobIds),
                ]);

                $bar->advance();
                continue;
            }

            $match = $this->findUniqueBuildMatch($millingCycle, $allowOneSecondDrift);

            if (!$match['ok']) {
                $stats['skipped_no_unique_match']++;

                $this->pushSample($skipSamples, $sampleSize, [
                    'case_id' => $candidate->case_id,
                    'case_number' => $candidate->case_number,
                    'reason' => $match['reason'],
                    'job_count' => count($jobIds),
                ]);

                $bar->advance();
                continue;
            }

            if ($match['strategy'] === 'exact') {
                $stats['exact_match_cases']++;
                $stats['exact_match_jobs'] += count($jobIds);
            } else {
                $stats['drift_match_cases']++;
                $stats['drift_match_jobs'] += count($jobIds);
            }

            $sample = [
                'case_id' => $candidate->case_id,
                'case_number' => $candidate->case_number,
                'job_count' => count($jobIds),
                'build_id' => $match['build']->id,
                'device_used' => $match['build']->device_used,
                'strategy' => $match['strategy'],
            ];

            if ($apply) {
                $updated = $this->applyBackfill($candidate, $jobIds, $match);

                if ($updated === 0) {
                    $stats['skipped_concurrent_change']++;

                    $this->pushSample($skipSamples, $sampleSize, $sample + [
                        'reason' => 'already_changed_before_update',
                    ]);

                    $bar->advance();
                    continue;
                }

                $stats['updated_cases']++;
                $stats['updated_jobs'] += $updated;
            }

            $this->pushSample($updateSamples, $sampleSize, $sample);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Metric', 'Value'],
            [
                ['Mode', $stats['mode']],
                ['Build era boundary', $stats['from_set_at']],
                ['Backup file', $stats['backup_file'] ?? '-'],
                ['Candidate cases', $stats['candidate_cases']],
                ['Candidate jobs', $stats['candidate_jobs']],
                ['Exact match cases', $stats['exact_match_cases']],
                ['Exact match jobs', $stats['exact_match_jobs']],
                ['One-second drift cases', $stats['drift_match_cases']],
                ['One-second drift jobs', $stats['drift_match_jobs']],
                ['Updated cases', $stats['updated_cases']],
                ['Updated jobs', $stats['updated_jobs']],
                ['Skipped: no build-era milling logs', $stats['skipped_no_build_era_logs']],
                ['Skipped: partial/duplicate milling cycle', $stats['skipped_partial_or_duplicate_cycle']],
                ['Skipped: no unique build match', $stats['skipped_no_unique_match']],
                ['Skipped: concurrent change', $stats['skipped_concurrent_change']],
            ]
        );

        if ($updateSamples !== []) {
            $this->newLine();
            $this->info($apply ? 'Updated sample:' : 'Recoverable sample:');
            $this->table(
                ['Case ID', 'Case Number', 'Jobs', 'Build ID', 'Device', 'Strategy'],
                array_map(function (array $row) {
                    return [
                        $row['case_id'],
                        $row['case_number'],
                        $row['job_count'],
                        $row['build_id'],
                        $row['device_used'],
                        $row['strategy'],
                    ];
                }, $updateSamples)
            );
        }

        if ($skipSamples !== []) {
            $this->newLine();
            $this->warn('Skipped sample:');
            $this->table(
                ['Case ID', 'Case Number', 'Jobs', 'Reason'],
                array_map(function (array $row) {
                    return [
                        $row['case_id'],
                        $row['case_number'],
                        $row['job_count'],
                        $row['reason'],
                    ];
                }, $skipSamples)
            );
        }

        if (!$apply) {
            $this->line('No database changes were written. Re-run with --apply after reviewing the dry-run output.');
        }

        return 0;
    }

    private function candidateCases(int $limit): Collection
    {
        $query = DB::table('jobs')
            ->join('materials', 'materials.id', '=', 'jobs.material_id')
            ->join('cases', 'cases.id', '=', 'jobs.case_id')
            ->whereNull('jobs.deleted_at')
            ->whereNull('jobs.milling_build_id')
            ->where('materials.mill', 1)
            ->select('jobs.case_id', 'cases.case_id as case_number')
            ->distinct()
            ->orderBy('jobs.case_id');

        $caseIds = array_values(array_filter(array_map('intval', (array) $this->option('case-id'))));
        if ($caseIds !== []) {
            $query->whereIn('jobs.case_id', $caseIds);
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get();
    }

    private function candidateMillJobIds(int $caseId): array
    {
        return DB::table('jobs')
            ->join('materials', 'materials.id', '=', 'jobs.material_id')
            ->where('jobs.case_id', $caseId)
            ->whereNull('jobs.deleted_at')
            ->whereNull('jobs.milling_build_id')
            ->where('materials.mill', 1)
            ->orderBy('jobs.id')
            ->pluck('jobs.id')
            ->map(function ($id) {
                return (int) $id;
            })
            ->all();
    }

    private function extractUniqueMillingCycle(int $caseId, string $fromSetAt): array
    {
        $logs = DB::table('case_logs')
            ->where('case_id', $caseId)
            ->where('created_at', '>=', $fromSetAt)
            ->orderBy('created_at')
            ->get(['stage', 'created_at']);

        $counts = [
            '2.1' => 0,
            '2.2' => 0,
            '2.3' => 0,
        ];
        $stageLogs = [
            '2.1' => collect(),
            '2.2' => collect(),
            '2.3' => collect(),
        ];

        foreach ($logs as $log) {
            $stage = (string) $log->stage;

            if (!array_key_exists($stage, $counts)) {
                continue;
            }

            $counts[$stage]++;
            $stageLogs[$stage]->push($log->created_at);
        }

        if (array_sum($counts) === 0) {
            return ['ok' => false, 'reason' => 'no_build_era_logs'];
        }

        $latestFinishedAt = $stageLogs['2.3']->last();

        if ($latestFinishedAt === null) {
            return [
                'ok' => false,
                'reason' => sprintf(
                    'partial_or_duplicate_cycle(no_2.3,2.1=%d,2.2=%d,2.3=%d)',
                    $counts['2.1'],
                    $counts['2.2'],
                    $counts['2.3']
                ),
            ];
        }

        $latestStartedAt = $stageLogs['2.2']
            ->filter(function ($createdAt) use ($latestFinishedAt) {
                return $createdAt <= $latestFinishedAt;
            })
            ->last();

        if ($latestStartedAt === null) {
            return [
                'ok' => false,
                'reason' => sprintf(
                    'partial_or_duplicate_cycle(no_2.2_before_2.3,2.1=%d,2.2=%d,2.3=%d)',
                    $counts['2.1'],
                    $counts['2.2'],
                    $counts['2.3']
                ),
            ];
        }

        $latestSetAt = $stageLogs['2.1']
            ->filter(function ($createdAt) use ($latestStartedAt) {
                return $createdAt <= $latestStartedAt;
            })
            ->last();

        if ($latestSetAt === null) {
            return [
                'ok' => false,
                'reason' => sprintf(
                    'partial_or_duplicate_cycle(no_2.1_before_2.2,2.1=%d,2.2=%d,2.3=%d)',
                    $counts['2.1'],
                    $counts['2.2'],
                    $counts['2.3']
                ),
            ];
        }

        return [
            'ok' => true,
            'set_at' => $latestSetAt,
            'started_at' => $latestStartedAt,
            'finished_at' => $latestFinishedAt,
        ];
    }

    private function findUniqueBuildMatch(array $millingCycle, bool $allowOneSecondDrift): array
    {
        $exactMatches = DB::table('builds')
            ->whereNotNull('device_used')
            ->where('set_at', $millingCycle['set_at'])
            ->where('started_at', $millingCycle['started_at'])
            ->where('finished_at', $millingCycle['finished_at'])
            ->get(['id', 'name', 'device_used', 'set_at', 'started_at', 'finished_at']);

        if ($exactMatches->count() === 1) {
            return [
                'ok' => true,
                'strategy' => 'exact',
                'build' => $exactMatches->first(),
            ];
        }

        if ($exactMatches->count() > 1) {
            return ['ok' => false, 'reason' => 'ambiguous_exact_match'];
        }

        if (!$allowOneSecondDrift) {
            return ['ok' => false, 'reason' => 'no_exact_match'];
        }

        $setAt = Carbon::parse($millingCycle['set_at']);
        $finishedAt = Carbon::parse($millingCycle['finished_at']);

        $driftMatches = DB::table('builds')
            ->whereNotNull('device_used')
            ->where('started_at', $millingCycle['started_at'])
            ->whereBetween('set_at', [
                $setAt->copy()->subSecond()->format('Y-m-d H:i:s'),
                $setAt->copy()->addSecond()->format('Y-m-d H:i:s'),
            ])
            ->whereBetween('finished_at', [
                $finishedAt->copy()->subSecond()->format('Y-m-d H:i:s'),
                $finishedAt->copy()->addSecond()->format('Y-m-d H:i:s'),
            ])
            ->get(['id', 'name', 'device_used', 'set_at', 'started_at', 'finished_at']);

        if ($driftMatches->count() === 1) {
            return [
                'ok' => true,
                'strategy' => 'one_second_drift',
                'build' => $driftMatches->first(),
            ];
        }

        if ($driftMatches->count() > 1) {
            return ['ok' => false, 'reason' => 'ambiguous_one_second_drift_match'];
        }

        return ['ok' => false, 'reason' => 'no_unique_build_match'];
    }

    private function applyBackfill(object $candidate, array $jobIds, array $match): int
    {
        return DB::transaction(function () use ($candidate, $jobIds, $match) {
            $updated = DB::table('jobs')
                ->whereIn('id', $jobIds)
                ->whereNull('milling_build_id')
                ->update([
                    'milling_build_id' => $match['build']->id,
                    'updated_at' => now(),
                ]);

            if ($updated === 0) {
                return 0;
            }

            $case = sCase::query()->select('id', 'case_id')->find($candidate->case_id);

            if ($case) {
                $this->logBackfillAudit($case, $jobIds, $match);
            }

            $this->appendBackupRecord([
                'applied_at' => now()->toDateTimeString(),
                'case_id' => (int) $candidate->case_id,
                'case_number' => $candidate->case_number,
                'job_ids' => $jobIds,
                'previous_milling_build_id' => null,
                'new_milling_build_id' => (int) $match['build']->id,
                'build_name' => $match['build']->name,
                'device_id' => (int) $match['build']->device_used,
                'strategy' => $match['strategy'],
            ]);

            return $updated;
        });
    }

    private function logBackfillAudit(sCase $case, array $jobIds, array $match): void
    {
        if (!class_exists(AuditLogger::class)) {
            return;
        }

        AuditLogger::log(
            'case_milling_build_backfilled',
            $case,
            [
                'case_id' => $case->id,
                'case_number' => $case->case_id,
                'job_ids' => $jobIds,
                'milling_build_id' => (int) $match['build']->id,
                'build_name' => $match['build']->name,
                'device_id' => (int) $match['build']->device_used,
                'strategy' => $match['strategy'],
            ],
            sprintf(
                'Backfilled milling_build_id %d for case %s via %s match (device_id: %s, jobs: %s)',
                $match['build']->id,
                $case->case_id,
                $match['strategy'],
                $match['build']->device_used,
                implode(',', $jobIds)
            )
        );
    }

    private function initializeBackupFile(): string
    {
        $directory = storage_path('app/backups');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR . 'milling-build-backfill-' . now()->format('Ymd_His') . '.jsonl';
        file_put_contents($path, '');

        return $path;
    }

    private function appendBackupRecord(array $record): void
    {
        if (empty($this->backupFilePath)) {
            return;
        }

        file_put_contents(
            $this->backupFilePath,
            json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL,
            FILE_APPEND
        );
    }

    private function pushSample(array &$rows, int $sampleSize, array $row): void
    {
        if ($sampleSize <= 0 || count($rows) >= $sampleSize) {
            return;
        }

        $rows[] = $row;
    }
}
