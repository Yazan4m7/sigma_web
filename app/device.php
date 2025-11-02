<?php

namespace App;

use App\Http\Traits\helperTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;





class Device extends Model
{
    use SoftDeletes;

    /**
     * Disable model caching to prevent incomplete object exceptions
     */
    protected $cacheable = false;

    /**
     * Attributes that are mass assignable
     */
    protected $fillable = ['name', 'type', 'sorting_order', 'img', 'hidden'];

    /**
     * Prevent serialization issues by implementing Serializable
     */
    public function __serialize(): array
    {
        return $this->toArray();
    }

    public function __unserialize(array $data): void
    {
        $this->fill($data);
        $this->exists = true;
    }

    public function __construct() {
        parent::__construct();


    }
    public function jobsSetArray($stage): array
    {
        $query = \App\job::where('is_set', 1)
            ->where('stage', $stage);

        // Apply stage-specific build ID filters
        if ($stage == 2) { // Milling
            $query->whereNotNull('milling_build_id');
        } elseif ($stage == 3) { // 3D Printing
            $query->whereNotNull('printing_build_id');
        } elseif ($stage == 4) { // pressing
            $query->whereNotNull('pressing_build_id');
        }

        return $query->get()->toArray() ?? [];
    }

    //public mixed $activeCasesIds;
    public function jobsActiveArray($stage): array
    {
        $query = \App\job::where('is_active', 1)
            ->where('stage', $stage);

        // Apply stage-specific build ID filters
        if ($stage == 2) { // Milling
            $query->whereNotNull('milling_build_id');
        } elseif ($stage == 3) { // 3D Printing
            $query->whereNotNull('printing_build_id');
        } elseif ($stage == 4) { // pressing
            $query->whereNotNull('pressing_build_id');
        }

        return $query->get()->toArray() ?? [];
    }



    public function isActive($stage): bool
    {
        return count($this->jobsActiveArray($stage)) > 0;
    }

    public function getUnitsSetAttribute($stage): int
    {
        return array_reduce($this->jobsSetArray($stage), function ($carry, $job) {
            return $carry + count(explode(',', $job['unit_num']));
        }, 0);
    }

    public function getUnitsActiveAttribute($stage): int
    {
        return array_reduce($this->jobsActiveArray($stage), function ($carry, $job) {
            return $carry + count(explode(',', $job['unit_num']));
        }, 0);
    }

    /**
     * Returns the count of active jobs for this device
     */
    public function getActiveJobsCount($stage = null): int
    {
        $query = \App\job::where('is_active', 1);

        if ($stage !== null) {
            $query->where('stage', $stage);
        }

        return $query->count();
    }

    public function getActiveBuildsCount()
    {
        return $this->builds()->whereNull('finished_at', )->whereNotNull("started_at")->count();
    }

    /**
     * Returns the count of set builds for this device
     */
    public function getSetBuildsCount(): int
    {
        return $this->builds()->whereNull('started_at', )->whereNotNull("set_at")->count();

    }

    /**
     * Returns the count of active cases for this device
     */
    public function getActiveCasesCount($stage = null): int
    {
        $activeCases = 0;
        $stageColumn = $this->getStageColumnName();

        if (!$stageColumn) {
            return 0;
        }

        // Get active builds for this device
        $builds = $this->builds()->whereNull('finished_at')->get();

        foreach ($builds as $build) {
            // Get jobs that have this build ID in the appropriate stage column and are active
            $jobs = \App\Job::where($stageColumn, $build->id)
                ->where('is_active', 1)
                ->get();

            foreach ($jobs as $job) {
                $units = $this->parseJobUnits($job->unit_num);
                $activeCases += count($units);
            }
        }

        return $activeCases;
    }

    public function countOfUnits($stage, $isActive)
    {   //stage : 3 , Device : 44 , waiting
        $count = 0;
        $stageColumn = $this->getStageColumnName();
        $builds = $this->builds()->get();
        foreach ($builds as $build) {
            // Get jobs that have this build ID in the appropriate stage column and are set but not active
            if ($isActive) {
                $jobs = \App\Job::where($stageColumn, $build->id)
                    ->where('stage', $stage)
                    ->where('is_set', 1)
                    ->where('is_active', 1)
                    ->get();
            } else {


                $jobs = \App\Job::where($stageColumn, $build->id)
                    ->where('is_set', 1)->where('stage', $stage)
                    ->where(function ($query) {
                        $query->whereNull('is_active')
                            ->orWhere('is_active', 0);
                    })
                    ->get();
            }
            foreach ($jobs as $job) {
                $units = $this->parseJobUnits($job->unit_num);
                $count += count($units);
            }
        }

        return $count;
    }

    private function getStageColumnName()
    {
        switch ($this->type) {
            case 2:
                return 'milling_build_id';
            case 3:
                return 'printing_build_id';
            case 4:
                return 'sintering_build_id';
            case 5:
                return 'pressing_build_id';
            default:
                return null;
        }
    }

    private function parseJobUnits($jobNum)
    {
        if (empty($jobNum)) {
            return [];
        }

        $units = [];

        if (str_contains($jobNum, ',')) {
            $units = explode(',', $jobNum);
        } elseif (str_contains($jobNum, ' ')) {
            $units = explode(' ', $jobNum);
        } else {
            $units[] = $jobNum;
        }

        // Trim whitespace from each unit
        return array_map('trim', array_filter($units));
    }

    public function builds()
    {
        return $this->hasMany(Build::class, 'device_used');
    }
}
