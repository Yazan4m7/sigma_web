@extends('layouts.app' ,[ 'pageSlug' => 'Materials Report' ])


@section('content')
    <link href="{{ asset('assets/css/sigma-reports-master.css') }}?v={{ filemtime(public_path('assets/css/sigma-reports-master.css')) }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sigma-reports-theme.css') }}?v={{ filemtime(public_path('assets/css/sigma-reports-theme.css')) }}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <div class="sigma-report-standard">
    <div class="report-filters-card">
        <form class="kt-form" method="GET" action="{{route('materials-report')}}">
            <!-- FILTERS ROW 1: Main Filters -->
            <div class="container-fluid">
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-lg-2 col-md-4 col-6">
                        <x-report-datetimepicker
                            name="from"
                            id="materials_from"
                            label="From Date:"
                            :value="request('from', now()->startOfMonth()->format('Y-m-d'))"
                            mode="date"
                            :required="true"
                        />
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <x-report-datetimepicker
                            name="to"
                            id="materials_to"
                            label="To Date:"
                            :value="request('to', now()->endOfMonth()->format('Y-m-d'))"
                            mode="date"
                            :required="true"
                        />
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        @if(isset($clients))
                            @php
                                $doctorOptions = $clients
                                    ->map(fn($doctor) => ['value' => $doctor->id, 'label' => $doctor->name])
                                    ->values()
                                    ->all();
                            @endphp
                            <x-report-dropdown
                                name="doctor[]"
                                id="doctor"
                                label="Doctor:"
                                :options="$doctorOptions"
                                :selected="$selectedClients ?? null"
                                :allSelected="in_array('all', (array) ($selectedClients ?? []), true)"
                                title="All Doctors"
                            />
                        @endif
                    </div>
                </div>

                <!-- BUTTONS ROW 2: Actions -->
                <div class="row g-3 align-items-center">
                    <div class="col-lg-4 col-md-4 col-12">
                        <button type="submit" class="btn btn-primary-enhanced">
                            <i class="fas fa-chart-line me-2"></i>  &nbsp;   Generate Report
                        </button>
                    </div>
                    <div class="col-lg-8 col-md-8 col-12 report-action-icons">
                        <i class="fas fa-file-excel printBtn d-none" id="exportExcelBtn" role="button" tabindex="0" aria-label="Export to Excel"></i>
                        <i class="fas fa-print printBtn" role="button" tabindex="0" aria-label="Print" onclick="window.print()"></i>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Total Amount Card -->
    <div class="container-fluid " style="padding-left: 0; padding-top: 0">
        <div class="row" style="background-color: transparent">
            <div class="col-lg-2 col-md-2 col-12">
                <div class="materials-total-card sigma-compact-summary-card">
                    <div>
                        <div class="materials-total-label">Total Amount</div>
                        <div class="materials-total-value">
                            <div class="materials-total-amount">{{number_format($totalAmount)}}</div>
                            <div class="materials-total-currency">JOD</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="container-fluid report-table-section" style="padding-top: 0">
        <div class="row">
            <div class="col-12">
                <table id="datatable" class="sigma-report-table table-plain" role="grid">
                    <thead>
                    <tr>
                        <th class="header-dark" style="color:white !important;">Doctor</th>
                        <th class="header-light">Patient</th>
                        <th class="text-center header-light">Zircon</th>
                        <th class="text-center header-light">Emax</th>
                        <th class="text-center header-light">Acrylic</th>
                        <th class="text-center header-light">Model</th>
                        <th class="text-center header-light">Amount</th>
                        <th class="text-center header-dark" style="color:white !important;    border-radius: 2px 14px 3px 3px;">Delivered On</th>
                    </tr>
                    </thead>


                    <tbody>
                    @foreach($cases as $case)
                            <tr onclick="window.location='{{route('view-invoice', $case->id)}}';">

                                <td class="primary-text">{{$case->client->name}}</td>
                                <td>{{$case->patient_name}}</td >
                                <td class="text-center">{{$case->materialUsed([1,20])}}</td>
                                <td class="text-center">{{$case->materialUsed([2])}}</td>
                                <td class="text-center">{{$case->materialUsed([3,4,6,7])}}</td>
                                <td class="text-center">{{$case->materialUsed([9,10])}}</td>
                                <td class="text-right currency">{{isset($case->invoice) ? number_format($case->invoice->amount): '0'}}</td>
                                <td class="secondary-text">{{substr($case->actual_delivery_date,0,10)}}</td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>
@endsection




@push('js')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Initialize DataTable with Excel export
        var table = $('#datatable').DataTable({
            dom: 'frtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i>',
                    className: 'printBtn',
                    title: 'Materials Report',
                    filename: 'materials_report_' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            "pageLength": 50,
            "searching": true,
            "lengthChange": true,
            "order": [[ 0, "asc" ]],
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });

        // Show export button when table is ready
        $('#exportExcelBtn').removeClass('d-none');

        // Trigger DataTable export on custom button click
        $('#exportExcelBtn').on('click', function() {
            table.button('.buttons-excel').trigger();
        });

        // FORCE DROPDOWN POSITIONING - Fix for DataTable interference
        function forceDropdownPositioning() {
            $('.bootstrap-select').each(function() {
                const $select = $(this);
                const $menu = $select.find('.dropdown-menu');

                $select.on('show.bs.dropdown', function() {
                    setTimeout(() => {
                        const selectRect = $select[0].getBoundingClientRect();
                        $menu.addClass('dropdown-force-visible').css({
                            'top': (selectRect.bottom + window.scrollY) + 'px',
                            'left': (selectRect.left + window.scrollX) + 'px',
                            'min-width': selectRect.width + 'px'
                        });
                    }, 10);
                });

                $select.on('hide.bs.dropdown', function() {
                    $menu.removeClass('dropdown-force-visible');
                });
            });
        }

        // Initialize immediately and after any potential DataTable initialization
        forceDropdownPositioning();
        setTimeout(forceDropdownPositioning, 100);
        setTimeout(forceDropdownPositioning, 500);
        setTimeout(forceDropdownPositioning, 1000);

    });
</script>
@endpush
