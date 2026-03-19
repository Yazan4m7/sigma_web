@extends('layouts.app' ,[ 'pageSlug' =>'My Collections'])

@section('content')
    <style>
        #datatable thead th:first-child,
        #datatable thead th:last-child {
            background-color: #408385 !important;
            color: #ffffff !important;
        }

        #datatable thead th:first-child {
            border-top-left-radius: 12px !important;
        }

        #datatable thead th:last-child {
            border-top-right-radius: 12px !important;
        }

        @media (max-width: 767px) {
            .sigma-list-page {
                display: flex;
                flex-direction: column;
            }

            .sigma-list-page > .sigma-summary-grid {
                order: 2;
            }

            .sigma-list-page > .sigma-table-free {
                order: 1;
            }
        }
    </style>
    <div class="sigma-list-page">
        <div class="sigma-summary-grid">
            <div class="sigma-summary-item">
                <div class="materials-total-card report-total-card sigma-compact-summary-card">
                    <div>
                        <span class="materials-total-label">Payments Total</span>
                        <div class="materials-total-value">
                            <span class="materials-total-amount sigma-summary-value--danger">{{ number_format($payments->sum('amount')) }}</span>
                            <span class="materials-total-currency">JOD</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sigma-table-free">
            <table id="datatable" class="table sunriseTable order-column display nowrap compact cell-border dataTable no-footer sigma-list-table" role="grid" aria-describedby="datatable_info">
                <thead>
                <tr role="row">
                    <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending">ID</th>
                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending">Doctor</th>
                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending">Amount</th>
                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Start date: activate to sort column ascending">Paid on</th>
                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending">Type</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr role="row">
                        <td class="sorting_1">{{ $payment->id }}</td>
                        <td>{{ $payment->client->name }}</td>
                        <td>{{ $payment->amount }} JOD</td>
                        <td>{{ substr($payment->created_at,0,16) }}</td>
                        <td>{{ $payment->from_bank ? $payment->notes : "Cash" }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('js')

                        <!-- Responsive and datatable js -->
                            <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#datatable').DataTable(
                                        {
                                            "pageLength": 25,
                                            "searching": false,
                                            "lengthChange": false,
                                            "order": [[ 4, "desc" ]],
                                        }
                                    );
                                } );
                            </script>
@endpush
