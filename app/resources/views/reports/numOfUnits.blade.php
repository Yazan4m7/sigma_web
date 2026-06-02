@extends('layouts.app' ,[ 'pageSlug' => 'Number of units Report'])

@section('content')
    <link href="{{ asset('assets/css/sigma-reports-master.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sigma-reports-theme.css') }}" rel="stylesheet">

    <!-- styles to carry on while printing -->

    <!-- styles for the view only -->

    <div class="report-filters-card">
        <form class="kt-form" method="GET" action="{{route('num-of-units-report')}}">
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
                        @if(isset($materials))
                            <label><i class="fas fa-cube"></i> Material:</label>
                            <select class="selectpicker clearOnAll" multiple name="material[]" id="material"
                                data-live-search="true" title="All Materials" data-hide-disabled="true">
                                <option value="all" {{(isset($selectedMaterials) && $selectedMaterials== 'all') ? 'selected' : ''}}>All</option>
                                @foreach($materials as $d)
                                    <option value="{{$d->id}}" {{(isset($selectedMaterials) && in_array($d->id ,$selectedMaterials)) ? 'selected' : ''}}>{{$d->name}}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        @if(isset($clients))
                            <label><i class="fas fa-user-md"></i> Doctors:</label>
                            <select class="selectpicker clearOnAll" multiple name="doctor[]" id="doctor"
                                data-live-search="true" title="All" data-hide-disabled="true">
                                <option value="all" {{(isset($selectedClients) && $selectedClients== 'all') ? 'selected' : ''}}>All</option>
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
                        <button type="submit" class="btn btn-primary-enhanced" style="height: 50px; padding: 12px 24px; font-size: 16px; font-weight: 600;">
                            <i class="fas fa-chart-line me-2"></i>   &nbsp;   Generate Report
                        </button>
                    </div>
                    <div class="col-lg-8 col-md-8 col-12 d-flex justify-content-end gap-2">

                            <i class="fas fa-print"></i>

                        <span style="width: 1vh"></span>

                            <i class="fas fa-eye"></i>

                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="sigmaPanel" style="">
        <div class="col-lg-12 col-sm-12">
            <div class=" ">
                <div class="">
                    <p class="text-muted"></p>
                    <div class="" style="overflow-x:auto;">
                        <div id="totalsTableHolder"></div>
                        <div id="monthlyBreakdown" style="display: none;">
                            @foreach($selectedMonths as $month)
                                @if($loop->index > 0)
                                    <div class="sigma-report-table-container" style="margin-bottom: 1.5rem;">
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
                                                            $totals[$client->id][$matId] += $currentTotal;
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
                            <div class="sigma-report-table-container">
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

                    var styling = document.getElementById("style");
                    newWin = window.open("");
                    newWin.document.write(styling.innerHTML);
                    newWin.document.write('<h3 style="float:left">Doctor Consumptions Report</h3>  <h4 style="float:right"> Date Printed :{!! date("d") !!} - {!! date("M") !!} - {!! date("Y") !!} </h4>');
                    $.each(tables, function (key, value) {
                        newWin.document.write(value.outerHTML);
                    });
                    newWin.print();
                    newWin.close();
                }

                $('.printBtn').on('click', function () {
                    printData();
                })

            </script>
    @endpush
