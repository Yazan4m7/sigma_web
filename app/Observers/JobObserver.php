<?php
namespace App\Observers;

use App\job;
use App\Support\OperationsDashboardCache;

class JobObserver
{
    public $afterCommit = true;

    public function created(Job $job)
    {
        $this->invalidate();
    }

    public function updated(Job $job)
    {
        $this->invalidate();
    }

    public function deleted(Job $job): void
    {
        $this->invalidate();
    }

    public function restored(Job $job): void
    {
        $this->invalidate();
    }

    public function forceDeleted(Job $job): void
    {
        $this->invalidate();
    }

    private function invalidate(): void
    {
        OperationsDashboardCache::bumpGeneration();
    }
}
