<?php

namespace App\Modules\DeviceStageBatches;

use App\sCase;
use Illuminate\Support\Collection;

class DeviceStageBatchRow
{
    public $case;
    public $id;
    public $jobs;
    public $stage;
    public $material_id;
    public $is_device_stage_batch = true;

    public function __construct(sCase $case, int $stage, int $materialId, Collection $jobs)
    {
        $this->case = $case;
        $this->id = $case->id;
        $this->stage = $stage;
        $this->material_id = $materialId;
        $this->jobs = $jobs->values();
    }

    public function __get($name)
    {
        if ($name === 'jobs') {
            return $this->jobs;
        }

        return $this->case->{$name};
    }

    public function __isset($name)
    {
        return isset($this->case->{$name});
    }

    public function __call($method, $arguments)
    {
        if ($method === 'unitsAmount') {
            return $this->unitsAmount($arguments[0] ?? -2);
        }

        return $this->case->{$method}(...$arguments);
    }

    public function isDeviceStageBatch(): bool
    {
        return true;
    }

    public function selectionValue(): string
    {
        return DeviceStageBatchService::token($this->id, $this->stage, $this->material_id);
    }

    public function dialogKey(): string
    {
        return $this->id . 's' . $this->stage . 'm' . $this->material_id;
    }

    public function batchMaterialName(): string
    {
        $job = $this->jobs->first(function ($job) {
            return $job->material;
        });

        return $job && $job->material ? $job->material->name : 'Material #' . $this->material_id;
    }

    public function batchLabel(): string
    {
        return $this->batchMaterialName();
    }

    public function unitsAmount($stage = -2): int
    {
        return $this->jobs->reduce(function ($total, $job) {
            if (!$job->material || $job->material->count_as_unit != 1) {
                return $total;
            }

            $units = array_filter(explode(',', (string) $job->unit_num), function ($unit) {
                return trim($unit) !== '';
            });

            return $total + max(1, count($units));
        }, 0);
    }
}
