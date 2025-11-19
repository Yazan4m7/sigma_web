<?php

namespace App\Observers;

use App\note;
use App\Services\AuditLogger;
use Illuminate\Support\Str;

class NoteObserver
{
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
    }
}
