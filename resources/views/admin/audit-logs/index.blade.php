@extends('layouts.app', ['pageSlug' => 'Audit Log'])

@push('css')
    <style>
        .audit-card {
            border: none;
            border-radius: 1rem;
        }

        .audit-card .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .05);
        }

        .audit-filter label {
            font-size: .8rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #8898aa;
        }

        .audit-filter .form-control,
        .audit-filter .custom-select {
            border-radius: .5rem;
        }

        /* Force readable dropdown text on light backgrounds */
        .audit-filter .custom-select,
        .audit-filter .custom-select option {
            color: #1f2937;
            background-color: #fff;
        }

        .audit-table thead th {
            border-top: none;
            text-transform: uppercase;
            font-size: .7rem;
            letter-spacing: .05em;
            color: #8898aa;
        }

        .audit-table tbody td {
            vertical-align: top;
            font-size: .875rem;
        }

        .audit-table pre {
            background: #f7fafc;
            padding: .5rem;
            border-radius: .5rem;
            font-size: .75rem;
            max-height: 150px;
            overflow: auto;
        }

        .audit-table td.details-column {
            max-width: 280px;
            min-width: 220px;
            vertical-align: top;
            white-space: normal;
        }

        .audit-table td.details-column details {
            margin-top: .4rem;
        }

        .audit-table td.details-column summary {
            cursor: pointer;
        }

        .audit-table td.details-column .properties-block {
            max-height: 120px;
            font-size: .72rem;
            overflow: auto;
            white-space: pre-wrap;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card audit-card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Audit Log</h5>
                        <small class="text-muted">Every tracked change in the system</small>
                    </div>
                    <span class="badge badge-pill badge-primary">{{ number_format($logs->total()) }} entries</span>
                </div>
                <div class="card-body">
                    <form method="GET" class="audit-filter mb-4">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="action">Action</label>
                                <select class="custom-select" id="action" name="action">
                                    <option value="">All actions</option>
                                    @foreach ($actions as $action)
                                        <option value="{{ $action }}" {{ ($filters['action'] ?? '') === $action ? 'selected' : '' }}>
                                            {{ \Illuminate\Support\Str::of($action)->replace('_', ' ')->upper() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="user_id">User</label>
                                <select class="custom-select" id="user_id" name="user_id">
                                    <option value="">Any user</option>
                                    @foreach ($users as $user)
                                        @php
                                            $label = $user->name_initials
                                                ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))
                                                ?: $user->username
                                                ?: ('#' . $user->id);
                                        @endphp
                                        <option value="{{ $user->id }}" {{ ($filters['user_id'] ?? '') == $user->id ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="subject_id">Subject ID</label>
                                <input type="number" class="form-control" id="subject_id" name="subject_id"
                                    value="{{ $filters['subject_id'] ?? '' }}">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="date_from">From</label>
                                <input type="date" class="form-control" id="date_from" name="date_from"
                                    value="{{ $filters['date_from'] ?? '' }}">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="date_to">To</label>
                                <input type="date" class="form-control" id="date_to" name="date_to"
                                    value="{{ $filters['date_to'] ?? '' }}">
                            </div>
                        </div>
                        <div class="form-row align-items-end">
                            <div class="form-group col-md-8">
                                <label for="search">Search text</label>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Notes, case number, IP..."
                                    value="{{ $filters['search'] ?? '' }}">
                            </div>
                            <div class="form-group col-md-4 d-flex justify-content-end">
                                <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-secondary mr-2">Reset</a>
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover audit-table">
                            <thead>
                                <tr>
                                    <th style="width: 13%;">Timestamp</th>
                                    <th style="width: 14%;">User</th>
                                    <th style="width: 13%;">Action</th>
                                    <th style="width: 15%;">Subject</th>
                                    <th>Description</th>
                                    <th style="width: 20%;">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold">{{ ui_view_date($log->created_at) }}</div>
                                            <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                        </td>
                                        <td>
                                            <div>{{ optional($log->user)->name_initials ?? optional($log->user)->username ?? 'System' }}</div>
                                            <small class="text-muted">{{ $log->ip_address ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-light text-uppercase">{{ $log->action }}</span>
                                        </td>
                                        <td>
                                            <div>{{ class_basename($log->subject_type ?? 'N/A') }}</div>
                                            <small class="text-muted">#{{ $log->subject_id ?? '—' }}</small>
                                        </td>
                                        <td>
                                            {{ $log->description ?? '—' }}
                                        </td>
                                        <td class="details-column">
                                            @php
                                                $metadata = $log->properties ?? [];
                                                $isCaseLog = \Illuminate\Support\Str::endsWith($log->subject_type ?? '', 'sCase');
                                                $deviceMeta = data_get($metadata, 'device');
                                                $payloadMeta = data_get($metadata, 'payload');
                                                $payloadFull = null;

                                                if ($payloadMeta !== null) {
                                                    if (is_scalar($payloadMeta)) {
                                                        $payloadFull = (string) $payloadMeta;
                                                    } else {
                                                        $payloadFull = json_encode($payloadMeta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                                        if ($payloadFull === false) {
                                                            $payloadFull = json_encode($payloadMeta, JSON_UNESCAPED_SLASHES);
                                                        }
                                                    }
                                                }

                                                $payloadSnippet = is_string($payloadFull)
                                                    ? \Illuminate\Support\Str::limit($payloadFull, 120)
                                                    : null;

                                                $remainingMetadata = $metadata;
                                                if ($isCaseLog) {
                                                    unset($remainingMetadata['device'], $remainingMetadata['payload']);
                                                }
                                            @endphp

                                            <div><strong>Agent:</strong> {{ \Illuminate\Support\Str::limit($log->user_agent ?? '—', 60) }}</div>
                                            @if ($isCaseLog && $deviceMeta)
                                                <div class="text-muted small mt-1">
                                                    <strong>Device:</strong>
                                                    {{ is_string($deviceMeta) ? $deviceMeta : json_encode($deviceMeta, JSON_UNESCAPED_SLASHES) }}
                                                </div>
                                            @endif

                                            @if ($payloadFull !== null)
                                                <div class="text-muted small mt-1">
                                                    <strong>Payload:</strong>
                                                    <span title="{{ e($payloadFull) }}">
                                                        {{ $payloadSnippet ?? $payloadFull }}
                                                    </span>
                                                </div>
                                                <details class="text-muted small">
                                                    <summary>View payload</summary>
                                                    <pre class="properties-block mb-0">{{ $payloadFull }}</pre>
                                                </details>
                                            @endif

                                            @if (!empty($remainingMetadata))
                                                <details class="text-muted small">
                                                    <summary>More metadata</summary>
                                                    <pre class="properties-block mb-0">{{ json_encode($remainingMetadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                </details>
                                            @elseif (!($isCaseLog && ($deviceMeta || $payloadFull !== null)))
                                                <span class="text-muted small d-block mt-1">No extra data</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No entries match your filters.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Showing {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }}
                            of {{ $logs->total() }}</small>
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
