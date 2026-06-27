@php
    $deliveryDateNotePattern = '/^Updated delivery date from \[(.*?)\] to \[(.*?)\]$/';
    $deliveryDateNotes = $case->notes->filter(function ($note) use ($deliveryDateNotePattern) {
        return preg_match($deliveryDateNotePattern, (string) $note->note);
    })->sortBy('created_at')->values();
    $regularNotes = $case->notes->reject(function ($note) use ($deliveryDateNotePattern) {
        return preg_match($deliveryDateNotePattern, (string) $note->note);
    });
@endphp

@if ($regularNotes->count() > 0 || $deliveryDateNotes->count() > 0)
    <hr>
    <label class="case-completion-dialog-label case-notes-label"><b>Notes:</b></label>

    @foreach ($regularNotes as $note)
        @php
            $noteEmployeeName = $note->writtenBy->first_name ?? $note->writtenBy->name_initials ?? '-';
            $noteValue = (string) $note->note;
            $noteHasArabic = preg_match('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}]/u', $noteValue);
            $noteHasLatin = preg_match('/[A-Za-z]/u', $noteValue);
            $noteDirectionClass = $noteHasArabic && $noteHasLatin ? 'sigma-case-note-text--mixed' : 'sigma-case-note-text--ltr';
        @endphp
        <div class="form-control note-container"
            style="height:fit-content;width:100%;margin-bottom: 8px;font-size:12px;padding:10px;display:block !important"
            disabled>
            <span class="noteHeader"
                style="font-weight:600;display:block !important">[{{ \Carbon\Carbon::parse($note->created_at)->format('j-M g:i A') }}] [{{ $noteEmployeeName }}] : </span>
            <span class="noteText sigma-case-note-text {{ $noteDirectionClass }}"
                style="display:block !important">{{ $note->note }}</span>
        </div>
    @endforeach

    @if ($deliveryDateNotes->count() > 0)
        @php
            $latestDeliveryDateNote = $deliveryDateNotes->last();
            preg_match($deliveryDateNotePattern, (string) $latestDeliveryDateNote->note, $latestDeliveryDateMatch);
            $latestOldDeliveryDate = $latestDeliveryDateMatch[1] ?? '-';
            $latestNewDeliveryDate = $latestDeliveryDateMatch[2] ?? '-';
            $latestDeliveryEmployee = optional($latestDeliveryDateNote->writtenBy)->name_initials
                ?? optional($latestDeliveryDateNote->writtenBy)->first_name
                ?? '-';
        @endphp
        <details class="sigma-case-delivery-notes-box">
            <summary class="sigma-case-delivery-notes-summary">
                <span class="sigma-case-delivery-note">{{ $latestDeliveryEmployee }} Updated D.Date [<span>{{ $latestOldDeliveryDate }}</span>] <span class="sigma-case-delivery-note-arrow" aria-hidden="true">&rarr;</span> [<span>{{ $latestNewDeliveryDate }}</span>]</span>
                <i class="fas fa-chevron-down sigma-case-delivery-notes-chevron" aria-hidden="true"></i>
            </summary>
            <div class="sigma-case-delivery-notes-list">
                @foreach ($deliveryDateNotes as $note)
                    @php
                        preg_match($deliveryDateNotePattern, (string) $note->note, $deliveryDateMatch);
                        $oldDeliveryDate = $deliveryDateMatch[1] ?? '-';
                        $newDeliveryDate = $deliveryDateMatch[2] ?? '-';
                        $deliveryEmployee = optional($note->writtenBy)->name_initials
                            ?? optional($note->writtenBy)->first_name
                            ?? '-';
                    @endphp
                    <div class="sigma-case-delivery-note">{{ $deliveryEmployee }} Updated D.Date [<span>{{ $oldDeliveryDate }}</span>] <span class="sigma-case-delivery-note-arrow" aria-hidden="true">&rarr;</span> [<span>{{ $newDeliveryDate }}</span>]</div>
                @endforeach
            </div>
        </details>
    @endif
@endif
