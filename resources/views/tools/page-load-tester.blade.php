@extends('layouts.app', ['pageSlug' => 'Page Load Tester'])

@push('css')
    <style>
        .page-test-hero {
            background: linear-gradient(135deg, #f7f9ff 0%, #eef2ff 100%);
            border-radius: 12px;
            border: 1px solid #e7ebff;
        }
        .page-test-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .page-test-card .card-header {
            background: #ffffff;
            border-bottom: 1px solid #eef2f7;
        }
        .page-test-table th {
            white-space: nowrap;
        }
        .page-test-meta {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .page-test-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .page-test-url {
            max-width: 360px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .page-test-badge {
            padding: 0.35rem 0.6rem;
            font-size: 0.75rem;
            border-radius: 999px;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card page-test-card">
                        <div class="card-body page-test-hero">
                            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                                <div>
                                    <h4 class="mb-1">Page Load Tester</h4>
                                    <div class="page-test-meta">Run a server-side request and log timing metrics. Choose stateless (consistent) or full session (realistic) testing.</div>
                                </div>
                                <div class="page-test-actions mt-3 mt-lg-0">
                                    <form method="POST" action="/tools/page-load-tester/clear" onsubmit="return confirm('Clear all results?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger">Clear All Results</button>
                                    </form>
                                </div>
                            </div>
                            <form method="POST" action="/tools/page-load-tester" class="mt-3">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label>Pick a page</label>
                                        <select name="page_key" class="form-control">
                                            <option value="">Select a page</option>
                                            @foreach($pages as $key => $page)
                                                <option value="{{ $key }}">{{ $page['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label>Custom URL (same host)</label>
                                        <input type="text" name="custom_url" class="form-control" placeholder="/cases or full URL">
                                    </div>
                                    <div class="form-group col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Test</button>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Timeout (seconds)</label>
                                        <input type="number" name="timeout" class="form-control" min="5" max="120" value="25">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Mode</label>
                                        <select name="mode" class="form-control">
                                            <option value="internal" selected>Internal (no HTTP)</option>
                                            <option value="http">HTTP (cURL)</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 d-flex align-items-end">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="use_session" value="1" id="useSessionToggle">
                                            <label class="form-check-label" for="useSessionToggle">
                                                Use full session (real user cookies, may be slower)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card page-test-card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Results</h4>
                        </div>
                        <div class="card-body">
                            @if($results->isEmpty())
                                <p class="text-muted mb-0">No results yet. Run a test to start logging.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped page-test-table">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Page</th>
                                            <th>URL</th>
                                            <th>Status</th>
                                            <th>Total (ms)</th>
                                            <th>TTFB (ms)</th>
                                            <th>Size (KB)</th>
                                            <th>Speed (KB/s)</th>
                                            <th>Mode</th>
                                            <th>Tester</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($results as $result)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($result->created_at)->format('Y-m-d H:i') }}</td>
                                                <td>{{ $result->label }}</td>
                                                <td class="page-test-url" title="{{ $result->url }}">{{ $result->url }}</td>
                                                <td>
                                                    @php
                                                        $status = (int) ($result->http_status ?? 0);
                                                        $statusClass = $status >= 200 && $status < 300 ? 'badge-success' : ($status >= 400 ? 'badge-danger' : 'badge-secondary');
                                                    @endphp
                                                    <span class="badge {{ $statusClass }} page-test-badge">{{ $result->http_status ?? '—' }}</span>
                                                </td>
                                                <td>{{ $result->total_time_ms ?? '—' }}</td>
                                                <td>{{ $result->starttransfer_time_ms ?? '—' }}</td>
                                                <td>
                                                    @if($result->size_download !== null)
                                                        {{ number_format($result->size_download / 1024, 1) }}
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($result->speed_download !== null)
                                                        {{ number_format($result->speed_download / 1024, 1) }}
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>{{ strtoupper($result->mode ?? 'HTTP') }}</td>
                                                <td>
                                                    {{ $result->name_initials ?? $result->first_name ?? '—' }}
                                                </td>
                                                <td>
                                                    <form method="POST" action="/tools/page-load-tester/{{ $result->id }}/delete" onsubmit="return confirm('Delete this result?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $results->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
