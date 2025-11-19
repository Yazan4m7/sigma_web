@extends('layouts.app', ['pageSlug' => 'Cases Without Invoice'])

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <style>
        :root {
            --primary-color: #5E72E4;
            --secondary-color: #F4F5F7;
            --text-color: #32325d;
            --text-color-light: #8898aa;
            --border-color: #E9ECEF;
            --white-color: #FFFFFF;
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.02), 0 2px 4px rgba(0, 0, 0, 0.05);
            --border-radius: .375rem;
        }

        /* Fix grey bar on top */
        .content {
            background: transparent !important;
        }

        .card {
            background-color: var(--white-color);
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: var(--white-color);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            font-size: .875rem;
            font-weight: 600;
            color: var(--text-color-light);
            margin-bottom: .5rem;
        }

        .form-control,
        .bootstrap-select .btn {
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: .5rem .75rem;
            font-size: .875rem;
            color: var(--text-color);
            transition: all 0.15s ease-in-out;
        }

        .form-control:focus,
        .bootstrap-select .btn:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(94, 114, 228, .25);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #4a5cc5;
            border-color: #4a5cc5;
        }

        .table thead th {
            background-color: var(--secondary-color);
            border-bottom: 1px solid var(--border-color);
            padding: .75rem 1rem;
            font-size: .8125rem;
            font-weight: 600;
            color: var(--text-color-light);
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .table tbody td {
            padding: .75rem 1rem;
            vertical-align: middle;
            font-size: .875rem;
            color: var(--text-color);
        }

        /* DataTables polish */
        .dataTables_length {
            display: none !important;
        }

        /* Hide "Show entries" */
        .dataTables_filter {
            display: none !important;
        }

        .dataTables_info {
            padding-top: .75rem;
            color: var(--text-color-light);
        }

        .dataTables_paginate {
            padding-top: .5rem;
        }

        /* Row hover + click UX */
        #cases-table tbody tr {
            cursor: pointer;
            transition: background-color .15s ease;
        }

        #cases-table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Modal beautification */
        .modal-content {
            border-radius: 20px;
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .modal-title {
            color: var(--text-color);
            font-weight: 600;
        }

        .case-meta {
            color: var(--text-color-light);
            font-size: .875rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Filter Cases</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('tools.invoice-check') }}">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="from-date">From Date</label>
                                <input type="date" id="from-date" name="from" class="form-control"
                                    value="{{ $from }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="to-date">To Date</label>
                                <input type="date" id="to-date" name="to" class="form-control"
                                    value="{{ $to }}">
                            </div>
                        </div><w>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               \</w>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="doctor-select">Doctor</label>
                                <select id="doctor-select" class="selectpicker form-control" name="doctor[]" multiple
                                    data-live-search="true" data-style="btn-light">
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ is_array(request('doctor')) && in_array($client->id, request('doctor')) ? 'selected' : '' }}>
                                            {{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Cases Without Invoice</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="cases-table" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Case ID</th>
                                <th>Patient Name</th>
                                <th>Doctor</th>
                                <th>Created At</th>
                                <th>Finished Date</th>
                                <th>Invoice Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cases as $case)
                                <tr class="clickable" data-toggle="modal" data-target="#caseDialog{{ $case->id }}">
                                    <td>{{ $case->id }}</td>
                                    <td>{{ $case->patient_name }}</td>
                                    <td>{{ $case->client->name }}</td>
                                    <td>{{ date('Y-m-d', strtotime($case->created_at)) }}</td>
                                    <td>{{ $case->actual_delivery_date ? date('Y-m-d', strtotime($case->actual_delivery_date)) : 'Not Finished' }}</td>
                                    <td>{{ optional($case->invoice)->amount ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($cases->isEmpty())
                    <div class="text-center py-3 text-muted">No cases found</div>
                @endif
            </div>
        </div>

        @if($cases->count())
            @foreach($cases as $case)
                <!-- Row actions modal -->
                <div class="modal fade" id="caseDialog{{ $case->id }}" tabindex="-1" role="dialog"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Case #{{ $case->id }} Actions</h5>
                                <button type="button" class="close" data-dismiss="modal"
                                                                                                                                                                                                           aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="case-meta"><strong>Doctor:</strong>
                                            {{ $case->client->name }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="case-meta"><strong>Patient:</strong>
                                            {{ $case->patient_name }}</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <div class="case-meta"><strong>Created:</strong>
                                            {{ date('Y-m-d', strtotime($case->created_at)) }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="case-meta"><strong>Finished:</strong>
                                            {{ $case->actual_delivery_date ? date('Y-m-d', strtotime($case->actual_delivery_date)) : 'Not Finished' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <form method="POST" action="{{ route('tools.invoice-check.issue') }}"
                                    class="mr-auto"
                                    onsubmit="return confirm('Issue invoice for case #{{ $case->id }}?');">
                                    @csrf
                                    <input type="hidden" name="case_id" value="{{ $case->id }}">
                                    <button type="submit" class="btn btn-success"><i
                                            class="fas fa-file-invoice"></i> Issue Invoice</button>
                                </form>
                                <a href="{{ route('view-case', ['id' => $case->id, 'stage' => -2]) }}"
                                    class="btn btn-info"><i class="far fa-file-alt"></i> View Case</a>
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
            $('#cases-table').DataTable({
                fixedHeader: true,
                responsive: true,
                bLengthChange: false, // hide "Show entries"
                dom: 'rtip', // clean controls
                order: [],
            });
        });
    </script>
@endpush
