@extends('layouts.app', ['pageSlug' => 'Quality Control Report'])

@section('content')
    <link href="{{ asset('assets/css/sigma-reports-master.css') }}?v={{ filemtime(public_path('assets/css/sigma-reports-master.css')) }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sigma-reports-theme.css') }}?v={{ filemtime(public_path('assets/css/sigma-reports-theme.css')) }}" rel="stylesheet">
    <!-- styles to carry on while printing -->

    <div class="sigma-report-standard">
    <div class="report-filters-card">
        <form class="kt-form" method="GET" action="{{ route('QC-report') }}">
            <!-- FILTERS ROW 1: Main Filters -->
            <div class="container-fluid">
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-lg-2 col-md-4 col-6">
                        <x-report-datetimepicker
                            name="from"
                            id="qc_from"
                            label="From Date:"
                            :value="request('from', now()->startOfMonth()->format('Y-m-d'))"
                            mode="date"
                            :required="true"
                        />
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <x-report-datetimepicker
                            name="to"
                            id="qc_to"
                            label="To Date:"
                            :value="request('to', now()->endOfMonth()->format('Y-m-d'))"
                            mode="date"
                            :required="true"
                        />
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        @php
                            $failureCauseOptions = $allFailureCauses
                                ->map(fn($cause) => ['value' => $cause->id, 'label' => $cause->text])
                                ->values()
                                ->all();
                            $selectedFailureCauseIds = $selectedFailureCauses->pluck('id')->toArray();
                        @endphp
                        <x-report-dropdown
                            name="causesInput[]"
                            id="causesInput"
                            label="Failure Cause:"
                            :options="$failureCauseOptions"
                            :selected="$selectedFailureCauseIds"
                            :allSelected="$allCausesSelected"
                            title="All Failure Causes"
                            icon="fas fa-exclamation-triangle"
                        />
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        @if (isset($clients))
                            @php
                                $doctorOptions = $clients
                                    ->map(fn($doctor) => ['value' => $doctor->id, 'label' => $doctor->name])
                                    ->values()
                                    ->all();
                            @endphp
                            <x-report-dropdown
                                name="doctor[]"
                                id="doctor"
                                label="Doctors:"
                                :options="$doctorOptions"
                                :selected="$selectedClients ?? null"
                                title="All"
                            />
                        @endif
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        @php
                            $failureTypeOptions = [
                                ['value' => 0, 'label' => 'Reject'],
                                ['value' => 1, 'label' => 'Repeat'],
                                ['value' => 2, 'label' => 'Modification'],
                                ['value' => 3, 'label' => 'Redo'],
                            ];
                        @endphp
                        <x-report-dropdown
                            name="failureTypeInput[]"
                            id="failureTypeInput"
                            label="Type of Failure:"
                            :options="$failureTypeOptions"
                            :selected="$typesSelected"
                            title="All"
                        />
                    </div>
                </div>

                <!-- BUTTONS ROW 2: Actions -->
                <div class="row g-3 align-items-center">
                    <div class="col-lg-4 col-md-4 col-12">
                        <button type="submit" class="btn btn-primary-enhanced">
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


    <div>
        <div class="col-lg-12 col-sm-12">
            <div class="">
                <div class="">
                    <div class="" style="overflow-x:auto;">
                        @php
                            $failuresDesc = [0 => 'Rejection', 1 => 'Repeat', 2 => 'Modification', 3 => 'Redo'];
                            $counterTest = 0;
                            $monthHasNoFailures = false;
                            $seenFailures = [];

                            // Calculate totals for main table
                            $mainTotals = [];
                            $totalUnits = 0;
                            foreach ($selectedMonths as $month) {
                                foreach ($failureLogs[$month] as $failLog) {
                                    $case = $failLog->case;
                                    $caseClient = $case ? $case->client : null;
                                    $caseClientId = $caseClient ? $caseClient->id : null;
                                    if (!$caseClientId) {
                                        continue;
                                    }
                                    $dedupeKey = $caseClientId . ':' . $failLog->failure_type . ':' . $case->id;
                                    if (isset($seenFailures[$dedupeKey])) {
                                        continue;
                                    }
                                    $seenFailures[$dedupeKey] = true;

                                    if (!in_array('all', $selectedClients)) {
                                        if (isset($selectedClients) && !in_array($caseClientId, $selectedClients)) {
                                            continue;
                                        }
                                    }

                                    $clientName = $caseClient->name ?? 'Unknown';
                                    $failureType = $failLog->failure_type;
                                    $units = $case ? $case->failedUnitsAmount($failLog->failure_type) : 0;

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
                </div>
            </div>
        </div>

  <style>
      .footer{display: none !important;}  /* 2 todo this */
  </style>
    </div>
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
