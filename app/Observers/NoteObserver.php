<?php

namespace App\Observers;

use App\note;
use App\Services\AuditLogger;
use App\Support\OperationsDashboardCache;
use Illuminate\Support\Str;

class NoteObserver
{
    public $afterCommit = true;

    public function created(note $note): void
    {
        AuditLogger::log(
            'case_note_added',
            $note,
            [
                'case_id' => $note->case_id,
                'note_excerpt' => Str::limit($note->note, 120),
            ],
            sprintf('Note added to case #%s', $note->case_id)
        );

        $this->invalidate();
    }

    public function updated(note $note): void
    {
        $this->invalidate();
    }

    public function deleted(note $note): void
    {
        $this->invalidate();
    }

    public function restored(note $note): void
    {
        $this->invalidate();
    }

    public function forceDeleted(note $note): void
    {
        $this->invalidate();
    }

    private function invalidate(): void
    {
        OperationsDashboardCache::bumpGeneration();
    }
}
