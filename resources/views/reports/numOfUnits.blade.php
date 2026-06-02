@extends('layouts.app' ,[ 'pageSlug' => 'Number of units Report'])

@section('content')
    <link href="{{ asset('assets/css/sigma-reports-master.css') }}?v={{ filemtime(public_path('assets/css/sigma-reports-master.css')) }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sigma-reports-theme.css') }}?v={{ filemtime(public_path('assets/css/sigma-reports-theme.css')) }}" rel="stylesheet">

    <!-- styles to carry on while printing -->

    <!-- styles for the view only -->

    <div class="sigma-report-standard">
    <div class="report-filters-card">
        <form class="kt-form" method="GET" action="{{route('num-of-units-report')}}">
            <!-- FILTERS ROW 1: Main Filters -->
            <div class="container-fluid">
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-lg-2 col-md-4 col-6">
                        <x-report-datetimepicker
                            name="from"
                            id="numunits_from"
                            label="From Date:"
                            :value="request('from', now()->startOfMonth()->format('Y-m-d'))"
                            mode="date"
                            :required="true"
                        />
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <x-report-datetimepicker
                            name="to"
                            id="numunits_to"
                            label="To Date:"
                            :value="request('to', now()->endOfMonth()->format('Y-m-d'))"
                            mode="date"
                            :required="true"
                        />
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        @if(isset($materials))
                            @php
                                $materialOptions = $materials
                                    ->map(fn($material) => ['value' => $material->id, 'label' => $material->name])
                                    ->values()
                                    ->all();
                            @endphp
                            <x-report-dropdown
                                name="material[]"
                                id="material"
                                label="Material:"
                                :options="$materialOptions"
                                :selected="$selectedMaterials ?? null"
                                :allSelected="$allMaterialsSelected ?? false"
                                title="All Materials"
                            />
                        @endif
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
                                label="Doctors:"
                                :options="$doctorOptions"
                                :selected="$selectedClients ?? null"
                                :allSelected="in_array('all', (array) ($selectedClients ?? []), true)"
                                title="All"
                            />
                        @endif
                    </div>
                </div>

                <!-- BUTTONS ROW 2: Actions -->
                <div class="row g-3 align-items-center">
                    <div class="col-lg-4 col-md-4 col-12">
                        <button type="submit" class="btn btn-primary-enhanced">
                            <i class="fas fa-chart-line me-2"></i>   &nbsp;   Generate Report
                        </button>
                    </div>
                    <div class="col-lg-8 col-md-8 col-12 report-action-icons">
                            <i class="fas fa-print printBtn" role="button" tabindex="0" aria-label="Print"></i>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="sigmaPanel container-fluid report-table-section" style="">
        <div class="col-lg-12 col-sm-12">
            <div class=" ">
                <div class="">
                    <p class="text-muted"></p>
                    <div class="" style="overflow-x:auto;">
                        <div id="totalsTableHolder"></div>
                        <div id="monthlyBreakdown" style="display: none;">
                            @foreach($selectedMonths as $month)
                                @if($loop->index > 0)
                                    <div style="margin-bottom: 1.5rem;">
                                        <div class="month-header-section">
                                            <h6 class="month-title"><i class="fas fa-calendar-alt me-2"></i>{{$month}}
                                            </h6>
                                        </div>
                                        <table class="printable sigma-report-table">
                                            <thead>
                                            <tr>
                                                <th class="header-dark" style="color:white  !important;">Dr Name</th>
                                                @foreach($selectedMaterials as $materialId)
                                                    @php $material = $materials->find($materialId); @endphp
                                                    @if($material)
                                                        <th class="text-center header-light">{{$material->name}}</th>
                                                    @endif
                                                @endforeach
                                                <th class="text-center header-dark" style="color:white !important;    border-radius: 2px 14px 3px 3px;">All</th>
                                            </tr>
                                            </thead>
                                            @endif
                                            <tbody>
                                            <!-- Main ROWS -->
                                            @foreach($clients as $client )
                                                <!-- if all is selected, don't check if client is selected or not, otherwise check each one by id -->
                                                @if(!in_array('all' ,$selectedClients))
                                                    @if(isset($selectedClients) && !in_array($client->id ,$selectedClients))
                                                        @continue;
                                                    @endif
                                                @endif

                                                <tr>
                                                    <td class="primary-text">{{$client->name}}</td>
                                                    @php
                                                        $docTotalUnits = 0;
                                                        $currentTotal = 0;
                                                    @endphp
                                                    @foreach($selectedMaterials as $matId)
                                                        @php
                                                            $currentTotal= $client->numOfUnitsByMaterial($matId,$month);
                                                            $docTotalUnits += $currentTotal;
                                                            $totalsArray[$month][$matId] += $currentTotal;
                                                        @endphp
                                                        <td class="text-center">{{$currentTotal}}</td>
                                                    @endforeach
                                                    <td class="text-center"><strong>{{$docTotalUnits}}</strong></td>
                                                    @php $totalsArray[$month][99] += $docTotalUnits; @endphp
                                                </tr>
                                            @endforeach


                                            <!-- Totals for whole lab Row -->
                                            <tr class="totals-row">
                                                <td><strong>Totals</strong></td>
                                                @foreach($selectedMaterials as $matId)
                                                    <td class="text-center">
                                                        <strong>{{$totalsArray[$month][$matId] ?? 0}}</strong></td>
                                                @endforeach
                                                <td class="text-center">
                                                    <strong>{{$totalsArray[$month][99] ?? 0}}</strong></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        @if($loop->index > 0)
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div id="totalsTableTempHolder">
                                <table class="printable sigma-report-table">
                                    <thead>
                                    <!--The Materials row -->
                                    <tr>
                                        <th class="header-dark" style="color:white !important;">Dr Name</th>
                                        @foreach($selectedMaterials as $materialId)
                                            @php $material = $materials->find($materialId); @endphp
                                            @if($material)
                                                <th class="text-center header-light">{{$material->name}}</th>
                                            @endif
                                        @endforeach
                                        <th class="text-center header-dark" style="color:white !important;    border-radius: 2px 14px 3px 3px;">All</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Main ROWS -->
                                    @foreach($clients as $client )
                                        <!-- if all is selected, don't check if client is selected or not, otherwise check each one by id -->
                                        @if(!in_array('all' ,$selectedClients))
                                            @if(isset($selectedClients) && !in_array($client->id ,$selectedClients))
                                                @continue;
                                            @endif
                                        @endif

                                        <tr>
                                            <td class="primary-text">{{$client->name}}</td>
                                            @php
                                                $docTotalUnits = 0;
                                                $currentTotal = 0;
                                            @endphp
                                            @foreach($selectedMaterials as $matId)
                                                @php
                                                    $currentTotal= $totals[$client->id][$matId];
                                                    $docTotalUnits += $currentTotal;
                                                    $totals2[$matId] += $currentTotal;
                                                @endphp
                                                <td class="text-center">{{$currentTotal}}</td>
                                            @endforeach
                                            <td class="text-center"><strong>{{$docTotalUnits}}</strong></td>
                                            @php $totals2[99] += $docTotalUnits; @endphp
                                        </tr>
                                    @endforeach

                                    <!-- Totals for whole lab Row -->
                                    <tr class="totals-row">
                                        <td><strong>Totals</strong></td>
                                        @foreach($selectedMaterials as $matId)
                                            <td class="text-center"><strong>{{$totals2[$matId] ?? 0}}</strong></td>
                                        @endforeach
                                        <td class="text-center"><strong>{{$totals2[99] ?? 0}}</strong></td>
                                    </tr>
                                    </tbody>
                                </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
        @endsection

        @push('js')

            <script>
                $(document).ready(function () {

                    $("#totalsTableHolder").html($("#totalsTableTempHolder").html());
                    $("#totalsTableTempHolder").html("");

                    // Initialize selectpicker
                    $('.selectpicker').selectpicker();

                    // Toggle monthly breakdown functionality
                    $("#toggleMonthlyBtn").click(function () {
                        const monthlyDiv = $("#monthlyBreakdown");
                        const button = $(this);

                        if (monthlyDiv.is(":visible")) {
                            monthlyDiv.slideUp(300);
                            button.text("Show Monthly Breakdown").removeClass("btn-warning").addClass("btn-info");
                        } else {
                            monthlyDiv.slideDown(300);
                            button.text("Hide Monthly Breakdown").removeClass("btn-info").addClass("btn-warning");
                        }
                    });
                });

                function printData() {
//        var table = $("#table1"),
//            tableWidth = table.outerWidth(),
//            pageWidth = 600,
//            pageCount = Math.ceil(tableWidth / pageWidth),
//            printWrap = $("<div></div>").insertAfter(table),
//            i,
//            printPage;
//        for (i = 0; i < pageCount; i++) {
//            printPage = $("<div></div>").css({
//                "overflow": "hidden",
//                "width": pageWidth,
//                "page-break-before": i === 0 ? "auto" : "always"
//            }).appendTo(printWrap);
//            table.clone().removeAttr("id").appendTo(printPage).css({
//                "position": "relative",
//                "left": -i * pageWidth
//            });
//        }
//        table.hide();
//        $(this).prop("disabled", true);
                    var tables = $('.printable');

                    if (tables.length === 0) {
                        return;
                    }
                    var headContent = Array.from(document.querySelectorAll('link[rel="stylesheet"], style'))
                        .map(function(node) { return node.outerHTML; })
                        .join('');
                    newWin = window.open("", "_blank", "width=1200,height=800");
                    if (!newWin) {
                        return;
                    }
                    newWin.document.write('<!DOCTYPE html><html><head><title>Number of Units Report</title>');
                    newWin.document.write(headContent);
                    newWin.document.write('</head><body>');
                    newWin.document.write('<div style="margin-bottom: 30px;"><h3 style="float:left">Doctor Consumptions Report</h3><h4 style="float:right"> Date Printed :{!! date("d") !!} - {!! date("M") !!} - {!! date("Y") !!} </h4><div style="clear: both;"></div></div>');
                    $.each(tables, function (key, value) {
                        newWin.document.write(value.outerHTML);
                    });
                    newWin.document.write('</body></html>');
                    newWin.document.close();
                    setTimeout(function() {
                        newWin.print();
                        newWin.close();
                    }, 250);
                }

                $('.printBtn').on('click', function () {
                    printData();
                })

            </script>
    @endpush
