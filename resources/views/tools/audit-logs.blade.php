@extends('layouts.app', ['pageSlug' => 'Audit Logs'])

@push('css')
<style>
    .card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .log-table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: .05em;
        border-bottom-width: 1px;
    }

    .log-table tbody td {
        vertical-align: top;
        font-size: 0.875rem;
    }

    .filters .form-control,
    .filters .custom-select {
        font-size: 0.85rem;
        border-radius: 10px;
    }

    /* Keep dropdown text visible on light cards */
    .filters .custom-select,
    .filters .custom-select option {
        color: #1f2937;
        background-color: #fff;
    }

    .filters label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #6b7280;
        margin-bottom: .25rem;
    }

    .properties-block {
        background: #f9fafb;
        border-radius: 10px;
        padding: .75rem;
        font-size: 0.8rem;
        max-height: 180px;
        overflow: auto;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .log-table td.details-column {
        max-width: 260px;
        min-width: 210px;
        vertical-align: top;
        white-space: normal;
    }

    .log-table td.details-column details {
        margin-top: .35rem;
    }

    .log-table td.details-column summary {
        cursor: pointer;
    }

    .log-table td.details-column .properties-block {
        max-height: 120px;
        font-size: .72rem;
        overflow: auto;
        white-space: pre-wrap;
    }
</style>
@endpush

@section('content')
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0">Audit Logs</h4>
                            <span class="text-muted small">Track every important action in Sigma</span>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-info">{{ $logs->total() }} records</span>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <form method="GET" action="{{ route('tools.audit-logs') }}" class="filters">
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="from">From</label>
                                <input type="date" class="form-control" name="from" id="from"
                                       value="{{ $filters['from'] }}">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="to">To</label>
                                <input type="date" class="form-control" name="to" id="to"
                                       value="{{ $filters['to'] }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="action">Action</label>
                                <select class="custom-select" id="action" name="action">
                                    <option value="">All actions</option>
                                    @foreach ($actions as $action)
                                        <option value="{{ $action }}" {{ $filters['action'] === $action ? 'selected' : '' }}>
                                            {{ Str::headline(str_replace('_', ' ', $action)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="user_id">User</label>
                                <select class="custom-select" id="user_id" name="user_id">
                                    <option value="">All users</option>
                                    @foreach ($users as $user)
                                        @php
                                            $displayName = trim($user->first_name . ' ' . $user->last_name) ?: ($user->name_initials ?? $user->username ?? ('User #' . $user->id));
                                        @endphp
                                        <option value="{{ $user->id }}" {{ (string) $filters['user_id'] === (string) $user->id ? 'selected' : '' }}>
                                            {{ $displayName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="search">Search</label>
                                <input type="text" class="form-control" name="search" id="search"
                                       placeholder="Text or ID" value="{{ $filters['search'] }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('tools.audit-logs') }}" class="btn btn-light mr-2">Reset</a>
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table log-table mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th style="min-width: 150px;">Timestamp</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Subject</th>
                            <th style="min-width: 220px;">Details</th>
                            <th>Source</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>
                                    <div class="font-weight-bold">{{ $log->created_at->format('Y-m-d H:i:s') }}</div>
                                    <div class="text-muted small">{{ $log->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    @if ($log->user)
                                        <div class="font-weight-bold">{{ $log->user->fullName() ?? $log->user->name_initials ?? $log->user->username }}</div>
                                        <div class="text-muted small">#{{ $log->user_id }}</div>
                                    @else
                                        <span class="badge badge-secondary">System</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-primary text-uppercase">
                                        {{ Str::headline(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                </td>
                                <td>
                                    <div>{{ $log->description ?? '—' }}</div>
                                </td>
                                <td>
                                    <div class="text-muted small">{{ $log->subject_type ?? 'N/A' }}</div>
                                    <div class="font-weight-bold">{{ $log->subject_id ?? '—' }}</div>
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

                                    @if ($isCaseLog && $deviceMeta)
                                        <div class="text-muted small">
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
                                        <span class="text-muted small">No extra data</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-muted small">{{ $log->ip_address ?? 'Unknown IP' }}</div>
                                    <div class="text-truncate" style="max-width: 220px;">
                                        <small class="text-muted">{{ $log->user_agent ?? 'No agent' }}</small>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No audit logs match your filters.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer border-0">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
