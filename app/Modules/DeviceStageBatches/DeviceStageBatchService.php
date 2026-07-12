<?php

namespace App\Modules\DeviceStageBatches;

use App\job;
use App\sCase;
use Illuminate\Support\Collection;

class DeviceStageBatchService
{
    private const TOKEN_PREFIX = 'batch';
    private const DEVICE_STAGES = [2, 3, 4, 5];

    public static function token($caseId, $stage, $materialId): string
    {
        return self::TOKEN_PREFIX . ':' . (int) $caseId . ':' . (int) $stage . ':' . (int) $materialId;
    }

    public function rowsForStage($cases, $stage, string $state = 'all'): Collection
    {
        $stage = (int) $stage;

        return collect($cases)->flatMap(function ($case) use ($stage, $state) {
            return $this->rowsForCase($case, $stage, $state);
        })->values();
    }

    public function resolveJobs($caseId, $stage, $materialId, string $state = 'all')
    {
        $jobs = job::with([
            'material:id,name,count_as_unit,is_dry,is_wet',
            'jobType:id,name,a_secondary_item',
            'subType:id,name,material_id',
            'assignedTo:id,name_initials',
            'implantR:id,name',
            'abutmentR:id,name',
        ])
            ->where('case_id', (int) $caseId)
            ->where('stage', (int) $stage)
            ->where('material_id', (int) $materialId)
            ->get();

        return $this->filterJobsForState($jobs, (int) $stage, $state)->values();
    }

    public function parseSelections($values, ?int $stage = null): array
    {
        $caseIds = [];
        $batches = [];

        foreach ($this->normalizeValues($values) as $value) {
            $batch = $this->parseToken($value);

            if ($batch) {
                if ($stage === null || (int) $batch['stage'] === (int) $stage) {
                    $batches[] = $batch;
                }
                continue;
            }

            if (is_numeric($value)) {
                $caseIds[] = (int) $value;
            }
        }

        return [
            'case_ids' => array_values(array_unique($caseIds)),
            'batches' => $this->uniqueBatches($batches),
        ];
    }

    public function caseIdsFromSelections($values): array
    {
        $selection = $this->parseSelections($values);
        $batchCaseIds = array_map(function ($batch) {
            return (int) $batch['case_id'];
        }, $selection['batches']);

        return array_values(array_unique(array_merge($selection['case_ids'], $batchCaseIds)));
    }

    public function materialIdsFromBatchSelections($values, ?int $stage = null): array
    {
        $selection = $this->parseSelections($values, $stage);

        return array_values(array_unique(array_map(function ($batch) {
            return (int) $batch['material_id'];
        }, $selection['batches'])));
    }

    private function rowsForCase(sCase $case, int $stage, string $state): Collection
    {
        if (!in_array($stage, self::DEVICE_STAGES, true)) {
            return collect([$case]);
        }

        $stageJobs = $this->jobsForCaseStage($case, $stage);
        $allMaterialGroups = $this->materialGroups($stageJobs);

        if ($allMaterialGroups->count() <= 1) {
            return collect([$case]);
        }

        $visibleGroups = $this->materialGroups($this->filterJobsForState($stageJobs, $stage, $state));

        if ($visibleGroups->isEmpty()) {
            return collect();
        }

        return $visibleGroups->map(function ($jobs, $materialId) use ($case, $stage) {
            return new DeviceStageBatchRow($case, $stage, (int) $materialId, $jobs);
        })->values();
    }

    private function jobsForCaseStage(sCase $case, int $stage): Collection
    {
        $jobs = $case->relationLoaded('jobs')
            ? $case->jobs
            : $case->jobs()
                ->with([
                    'material:id,name,count_as_unit,is_dry,is_wet',
                    'jobType:id,name,a_secondary_item',
                    'subType:id,name,material_id',
                    'assignedTo:id,name_initials',
                    'implantR:id,name',
                    'abutmentR:id,name',
                ])
                ->where('stage', $stage)
                ->get();

        return collect($jobs)->filter(function ($job) use ($stage) {
            return (int) $job->stage === $stage && !empty($job->material_id);
        })->values();
    }

    private function materialGroups(Collection $jobs): Collection
    {
        return $jobs->filter(function ($job) {
            return !empty($job->material_id);
        })->groupBy(function ($job) {
            return (string) $job->material_id;
        });
    }

    private function filterJobsForState(Collection $jobs, int $stage, string $state): Collection
    {
        if ($state === 'all') {
            return $jobs;
        }

        return $jobs->filter(function ($job) use ($stage, $state) {
            if ($state === 'waiting') {
                return $this->isWaitingJob($job, $stage);
            }

            if ($state === 'active') {
                return $this->isActiveJob($job, $stage);
            }

            return true;
        });
    }

    private function isWaitingJob($job, int $stage): bool
    {
        if ($stage === 2) {
            return empty($job->is_set);
        }

        if ($stage === 3) {
            return empty($job->is_set) && empty($job->is_active) && empty($job->printing_build_id);
        }

        if (in_array($stage, [4, 5], true)) {
            return $job->is_active === null;
        }

        return true;
    }

    private function isActiveJob($job, int $stage): bool
    {
        if ($stage === 2) {
            return (int) $job->is_set === 1;
        }

        if ($stage === 3) {
            return (int) $job->is_set === 1 || (int) $job->is_active === 1 || !empty($job->printing_build_id);
        }

        if (in_array($stage, [4, 5], true)) {
            return (int) $job->is_set === 1 || $job->is_active !== null;
        }

        return true;
    }

    private function normalizeValues($values): array
    {
        if (empty($values)) {
            return [];
        }

        if (is_array($values)) {
            $values = implode(',', $values);
        }

        return array_values(array_filter(array_map('trim', explode(',', (string) $values)), function ($value) {
            return $value !== '';
        }));
    }

    private function parseToken(string $value): ?array
    {
        $parts = explode(':', $value);

        if (count($parts) !== 4 || $parts[0] !== self::TOKEN_PREFIX) {
            return null;
        }

        if (!is_numeric($parts[1]) || !is_numeric($parts[2]) || !is_numeric($parts[3])) {
            return null;
        }

        return [
            'case_id' => (int) $parts[1],
            'stage' => (int) $parts[2],
            'material_id' => (int) $parts[3],
        ];
    }

    private function uniqueBatches(array $batches): array
    {
        $seen = [];
        $unique = [];

        foreach ($batches as $batch) {
            $key = $batch['case_id'] . ':' . $batch['stage'] . ':' . $batch['material_id'];
            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $unique[] = $batch;
        }

        return $unique;
    }
}
