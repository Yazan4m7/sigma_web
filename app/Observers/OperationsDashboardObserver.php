<?php

namespace App\Observers;

use App\Support\OperationsDashboardCache;

class OperationsDashboardObserver
{
    public $afterCommit = true;

    public function created($model): void
    {
        $this->invalidate();
    }

    public function updated($model): void
    {
        $this->invalidate();
    }

    public function deleted($model): void
    {
        $this->invalidate();
    }

    public function restored($model): void
    {
        $this->invalidate();
    }

    public function forceDeleted($model): void
    {
        $this->invalidate();
    }

    private function invalidate(): void
    {
        OperationsDashboardCache::bumpGeneration();
    }
}
