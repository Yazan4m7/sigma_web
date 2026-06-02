@extends('layouts.app', ['pageSlug' => 'Quality Control Report'])

@section('content')
    <link href="{{ asset('assets/css/sigma-reports-master.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sigma-reports-theme.css') }}" rel="stylesheet">
    <!-- styles to carry on while printing -->


    <div class="report-filters-card">
        <form class="kt-form" method="GET" action="{{ route('QC-report') }}">
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
                        <label><i class="fas fa-exclamation-triangle"></i> Failure Cause:</label>
                        <select class="selectpicker clearOnAll" multiple name="causesInput[]" id="causesInput"
                            data-live-search="true" title="All Failure Causes" data-hide-disabled="true">
                            @if ($allCausesSelected)
                                <option value="all" selected>All</option>
                                @foreach ($allFailureCauses as $d)
                                    <option value="{{ $d->id }}">{{ $d->text }}</option>
                                @endforeach
                            @else
                                @php $idsOfSelectedCauses = $selectedFailureCauses->pluck('id')->toArray(); @endphp
                                <option value="all">All</option>
                                @foreach ($allFailureCauses as $d)
                                    <option value="{{ $d->id }}"
                                        {{ in_array($d->id, $idsOfSelectedCauses) ? 'selected' : '' }}>{{ $d->text }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        @if (isset($clients))
                            <label><i class="fas fa-user-md"></i> Doctors:</label>
                            <select class="selectpicker clearOnAll" multiple name="doctor[]" id="doctor"
                                data-live-search="true" title="All" data-hide-disabled="true">
                                <option value="all"
                                    {{ isset($selectedClients) && $selectedClients == 'all' ? 'selected' : '' }}>
                                    All
                                </option>
                                @foreach ($clients as $d)
                                    <option value="{{ $d->id }}"
                                        {{ isset($selectedClients) && in_array($d->id, $selectedClients) ? 'selected' : '' }}>
                                        {{ $d->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        <label><i class="fas fa-exclamation-circle"></i> Type of Failure:</label>
                        <select class="selectpicker clearOnAll" multiple name="failureTypeInput[]"
                            id="failureTypeInput" data-live-search="true" title="All" data-hide-disabled="true">
                            <option value="all" {{ in_array('all', $typesSelected) ? 'selected' : '' }}>All</option>
                            <option value="0" {{ in_array(0, $typesSelected) ? 'selected' : '' }}>Reject</option>
                            <option value="1" {{ in_array(1, $typesSelected) ? 'selected' : '' }}>Repeat</option>
                            <option value="2" {{ in_array(2, $typesSelected) ? 'selected' : '' }}>Modification</option>
                            <option value="3" {{ in_array(3, $typesSelected) ? 'selected' : '' }}>Redo</option>
                        </select>
                    </div>
                </div>

                <!-- BUTTONS ROW 2: Actions -->
                <div class="row g-3 align-items-center">
                    <div class="col-lg-4 col-md-4 col-12">
                        <button type="submit" class="btn btn-primary-enhanced" style="height: 50px; padding: 12px 24px; font-size: 16px; font-weight: 600; width: 70%;">
                            <i class="fas fa-chart-line me-2"></i>   &nbsp;   Generate Report
                       </button>
                    </div>
                    <div class="col-lg-8 col-md-8 col-12 ">

                            <i class="fas fa-print printBtn"></i>

                    </div>
                </div>
            </div>
        </form>
    </div>


    <div class="sigma-table-container">
        <div class="col-lg-12 col-sm-12">
            <div class="">
                <div class="">
                    <div class="" style="overflow-x:auto;">
                        @php
                            $failuresDesc = [0 => 'Rejection', 1 => 'Repeat', 2 => 'Modification', 3 => 'Redo'];
                            $counterTest = 0;
                            $monthHasNoFailures = false;

                            // Calculate totals for main table
                            $mainTotals = [];
                            $totalUnits = 0;
                            foreach ($selectedMonths as $month) {
                                foreach ($failureLogs[$month] as $failLog) {
                                    if (!in_array('all', $selectedClients)) {
                                        if (isset($selectedClients) && !in_array($failLog->case->client->id, $selectedClients)) {
                                            continue;
                                        }
                                    }

                                    $clientName = $failLog->case->client->name ?? 'Unknown';
                                    $failureType = $failLog->failure_type;
                                    $units = isset($failLog->case) ? $failLog->case->failedUnitsAmount($failLog->failure_type) : 0;

                                    if (!isset($mainTotals[$clientName])) {
                                        $mainTotals[$clientName] = ['Rejection' => 0, 'Repeat' => 0, 'Modification' => 0, 'Redo' => 0, 'total' => 0];
                                    }

                                    $mainTotals[$clientName][$failuresDesc[$failureType]] += $units;
                                    $mainTotals[$clientName]['total'] += $units;
                                    $totalUnits += $units;
                                }
                            }
                        @endphp

                        <!-- Main Summary Table -->
                        <div id="mainSummaryTable" class="sigma-report-table-container">
                            <table class="printable sigma-report-table">
                                <thead>
                                    <tr>
                                        <th class="header-dark" style="color:white !important;">Doctor Name</th>
                                        <th class="text-center header-light">Rejection</th>
                                        <th class="text-center header-light">Repeat</th>
                                        <th class="text-center header-light">Modification</th>
                                        <th class="text-center header-light">Redo</th>
                                        <th class="text-center header-dark" style="color:white !important;    border-radius: 2px 14px 3px 3px;">Total Units</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($mainTotals) == 0)
                                        <tr>
                                            <td colspan="6" class="text-center" style="padding: 2rem; color: #10b981; font-weight: 600;">
                                                <i class="fas fa-check-circle me-2"></i>No Quality Control Incidents Found
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($mainTotals as $doctorName => $totals)
                                            <tr>
                                                <td class="primary-text">{{ $doctorName }}</td>
                                                <td class="text-center">{{ $totals['Rejection'] }}</td>
                                                <td class="text-center">{{ $totals['Repeat'] }}</td>
                                                <td class="text-center">{{ $totals['Modification'] }}</td>
                                                <td class="text-center">{{ $totals['Redo'] }}</td>
                                                <td class="text-center"><strong>{{ $totals['total'] }}</strong></td>
                                            </tr>
                                        @endforeach

                                        <!-- Totals Row -->
                                        <tr class="totals-row">
                                            <td><strong>Totals</strong></td>
                                            <td class="text-center"><strong>{{ collect($mainTotals)->sum('Rejection') }}</strong></td>
                                            <td class="text-center"><strong>{{ collect($mainTotals)->sum('Repeat') }}</strong></td>
                                            <td class="text-center"><strong>{{ collect($mainTotals)->sum('Modification') }}</strong></td>
                                            <td class="text-center"><strong>{{ collect($mainTotals)->sum('Redo') }}</strong></td>
                                            <td class="text-center"><strong>{{ $totalUnits }}</strong></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Monthly Breakdown Tables (hidden by default) -->
                        <div id="monthlyBreakdown" style="display: none;">
                        @foreach ($selectedMonths as $month)
                            @if($loop->index > 0)
                            {{ $monthHasNoFailures = false }}
                            @php
                                if ($amountOfCases[$month] == 0) {
                                    $monthHasNoFailures = true;
                                }
                            @endphp
                            <div class="sigma-report-table" style="margin-top: 1.5rem;">
                            <div class="month-header-section">
                                <h6 class="month-title"><i class="fas fa-calendar-alt me-2"></i>{{ $month }} {{ $amountOfCases != 0 ? '(' . $amountOfCases[$month] . ') Cases' : '' }}</h6>
                            </div>
                            <table class="printable sigma-report-table">
                                <thead>
                            @endif


                                <tbody>
                                    <!-- The Months row -->

                                    @if ($monthHasNoFailures)
                                        <tr>
                                            <td colspan="6" class="text-center" style="padding: 2rem; color: #10b981; font-weight: 600;">
                                                <i class="fas fa-check-circle me-2"></i>No Incidents
                                            </td>
                                        </tr>
                                        @continue
                                    @endif

                                    <!--The MAIN row -->
                                    <thead>
                                    <tr>
                                    <th class="header-dark" style="color:white !important;">Dr Name</th>
                                        <th class="header-light">Patient</th>
                                        <th class="header-light">Status</th>
                                        <th class="header-light">Causes</th>
                                        <th class="text-center header-light"># of Units</th>
                                        <th class="header-dark" style="color:white !important;    border-radius: 2px 14px 3px 3px;">Date Failure Registered</th>
                                    </tr>
                                    </thead>
                                    <!-- Client ROWS -->

                                    @foreach ($failureLogs[$month] as $failLog)
                                        @if (!in_array('all', $selectedClients))
                                            @if (isset($selectedClients) && !in_array($failLog->case->client->id, $selectedClients))
                                                @continue;
                                            @endif
                                        @endif

                                        <!-- if all is selected, dont check if client is selected or not, otherwise check each one by id -->
                                        {{-- @if (!in_array('all', $selectedClients)) --}}
                                        {{-- @if (isset($selectedClients) && !in_array($client->id, $selectedClients)) --}}
                                        {{-- @continue; --}}
                                        {{-- @endif --}}
                                        {{-- @endif --}}

                                        <tr>
                                            <td class="primary-text">
                                                {{ $failLog->case->client->name ?? 'Case Not found' }}</td>
                                            <td>{{ $failLog->case->patient_name ?? 'Case Not found' }}</td>
                                            <td>
                                                <span class="sigma-status-badge {{ strtolower($failuresDesc[$failLog->failure_type]) }}">
                                                    {{ $failuresDesc[$failLog->failure_type] }}
                                                </span>
                                            </td>
                                            <td class="secondary-text">{{ $failLog->causeObject->text }}</td>
                                            <td class="text-center">
                                                @php
                                                    if (isset($failLog->case)) {
                                                        $numOfUnits = $failLog->case->failedUnitsAmount(
                                                            $failLog->failure_type,
                                                        );
                                                        $counterTest =
                                                            $counterTest +
                                                            $failLog->case->failedUnitsAmount($failLog->failure_type);
                                                    } else {
                                                        $numOfUnits = 'Case Not found';
                                                    }
                                                @endphp
                                                <strong>{{ $numOfUnits }}</strong>
                                            </td>
                                            <td class="secondary-text">{{ substr($failLog->created_at, 0, -3) }}</td>
                                        </tr>
                                    @endforeach


                                    <!-- Totals for whole lab Row -->
                                    {{-- <tr style=""> --}}
                                    {{-- <td class="xl669957">Totals</td> --}}

                                    {{-- @foreach ($labLevelTotal[$month] as $total) --}}
                                    {{-- <td class="totalsRow" style="">{{$total}}</td> --}}
                                    {{-- @endforeach --}}
                                    {{-- <td class="totalsRow" style="">{{array_sum($labLevelTotal[$month])}}</td> --}}
                                    {{-- </tr> --}}
                                </tbody>
                            </table>
                            </div>
                            @if($loop->index > 0)
                            </div>
                            @endif
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

  <style>
      .footer{display: none !important;}  /* 2 todo this */
  </style>
@endsection

@push('js')
    <script src="{{ asset('assets/js/tether.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(".toggle-group > *").addClass("unstyled");
            $(".toggle").addClass("unstyled");
            $(".toggle-group > label").addClass("toggleInnerBtns");
            $("#numOfUnitsFailed").html({!! $counterTest !!});

            console.log("Amount of units : ");
            console.log({!! $counterTest !!});

            // Monthly breakdown toggle functionality
            let monthlyVisible = false;

            $('#monthly-breakdown-toggle').on('click', function() {
                const $button = $(this);
                const $monthlyTables = $('#monthlyBreakdown');

                if (monthlyVisible) {
                    // Hide monthly tables
                    $monthlyTables.slideUp(300);
                    $button.removeClass('btn-success').addClass('btn-outline-secondary')
                           .html('<i class="fas fa-eye"></i>')
                           .attr('title', 'Show Monthly Breakdown');
                    monthlyVisible = false;
                } else {
                    // Show monthly tables
                    $monthlyTables.slideDown(300);
                    $button.removeClass('btn-outline-secondary').addClass('btn-success')
                           .html('<i class="fas fa-eye-slash"></i>')
                           .attr('title', 'Hide Monthly Breakdown');
                    monthlyVisible = true;
                }
            });
        });

        function printData() {
                   var table = $("#table1"),
                       tableWidth = table.outerWidth(),
                       pageWidth = 600,
                       pageCount = Math.ceil(tableWidth / pageWidth),
                       printWrap = $("<div></div>").insertAfter(table),
                       i,
                       printPage;
                   for (i = 0; i < pageCount; i++) {
                       printPage = $("<div></div>").css({
                           "overflow": "hidden",
                           "width": pageWidth,
                           "page-break-before": i === 0 ? "auto" : "always"
                       }).appendTo(printWrap);
                       table.clone().removeAttr("id").appendTo(printPage).css({
                           "position": "relative",
                           "left": -i * pageWidth
                       });
                   }
                   table.hide();
                   $(this).prop("disabled", true);
            var tables = $('.printable');

            var styling = document.getElementById("style");
            newWin = window.open("");
            newWin.document.write(styling.innerHTML);
            newWin.document.write('<h3 style="float:left">Quality Control Report</h3> ' +
                ' <h4 style="float:right"> Date Printed :{!! date('d') !!} - {!! date('M') !!} - {!! date('Y') !!} </h4>'
                );
            $.each(tables, function(key, value) {
                newWin.document.write(value.outerHTML);
            });
            newWin.print();
            newWin.close();
        }
        $('.printBtn').on('click', function() {
            printData();
        })
    </script>
@endpush
