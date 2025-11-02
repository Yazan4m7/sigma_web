@extends('layouts.app' ,[ 'pageSlug' => 'Materials Report' ])


@section('content')
    <link href="{{ asset('assets/css/sigma-reports-master.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sigma-reports-theme.css') }}" rel="stylesheet">

    <div class="report-filters-card">
        <form class="kt-form" method="GET" action="{{route('materials-report')}}">
            <!-- FILTERS ROW 1: Main Filters -->
            <div class="container-fluid">
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-lg-2 col-md-4 col-6">
                        <label><i class="fas fa-calendar-alt"></i> From Date:</label>
                        <input class="form-control" type="date" name="from" value="{{request('from', now()->startOfMonth()->format('Y-m-d'))}}">
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <label><i class="fas fa-calendar-alt"></i> To Date:</label>
                        <input class="form-control" type="date" name="to" value="{{request('to', now()->endOfMonth()->format('Y-m-d'))}}">
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        @if(isset($clients))
                            <label ><i class="fas fa-user-md"></i> Doctor:</label>
                            <select class="selectpicker clearOnAll" multiple name="doctor[]" id="doctor" data-live-search="true" title="All Doctors" data-hide-disabled="true">
                                <option value="all" selected>All</option>
                                @foreach($clients as $d)
                                    <option value="{{$d->id}}" {{(isset($selectedClients) && in_array($d->id ,$selectedClients)) ? 'selected' : ''}}>{{$d->name}}</option>
                                @endforeach
                            </select>
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
                    <div class="col-lg-8 col-md-8 col-12 d-flex justify-content-end">

                            <i class="fas fa-print me-2"></i>

                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Total Amount Card -->
    <div class="container-fluid mt-3 mb-3">
        <div class="row">
                <div class="col-lg-3 col-md-4 col-12">
                <div class="report-summary-card" style="background: linear-gradient(135deg, #2c5766 0%, #3a7080 100%); border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(44, 87, 102, 0.3);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 style="color: rgba(255, 255, 255, 0.9); margin-bottom: 8px; font-size: 14px; font-weight: 500;">Total Amount</h5>
                            <h2 style="color: white; margin: 0; font-size: 32px; font-weight: 700;">
                                {{number_format($totalAmount)}} <span style="font-size: 18px; font-weight: 600;">JOD</span>
                            </h2>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="sigma-table-container">
                    <div class="sigma-report-table-container">
                        <table id="datatable" class="sigma-report-table" role="grid">
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
                                        <td class="text-right currency">{{isset($case->invoice) ? number_format($case->invoice->amount) . ' JOD' : '0 JOD'}}</td>
                                        <td class="secondary-text">{{substr($case->actual_delivery_date,0,10)}}</td>
                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection




@push('js')

<script type="text/javascript">
    //        $(document).ready(function() {
    //            $('#datatable').DataTable({
    //                dom: 'Bfrtip',
    //                buttons: [ 'csv', 'excel', 'pdf', 'print' ],
    //                "pageLength": 25,
    //                "searching": false,
    //                "lengthChange": false,
    //                "order": [[ 4, "desc" ]]
    //            });
    //        });
    $(document).ready(function() {


            //{ dom: 'Bfrtip', buttons: ['colvis', 'excel', 'print'] }
            //  "bJQueryUI": true
            // "sDom": 'l<"H"Rf>t<"F"ip>'

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
