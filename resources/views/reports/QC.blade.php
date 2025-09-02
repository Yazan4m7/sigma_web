@extends('layouts.app' ,[ 'pageSlug' => 'Quality Control Report'])

@section('content')
    <link href="{{asset('assets/css/picker.css')}}" rel="stylesheet">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!-- styles to carry on while printing -->
    <div id="style">
        <style>
            footer{display:none}
            .sigmaPanel {
                padding-bottom:10px;
                padding-top:10px;
                margin-bottom:15px;
                margin-top:15px;
                background-color: white;
            }
            .row {
                background-color: transparent;
            }
            .no-left-top-border {
                border-top-color: transparent;
                border-top-style: solid;
                border-top-width: 1px;

                border-left-color: transparent;
                border-left-style: solid;
                border-left-width: 1px;
            }
            td, tr {
                width: fit-content;
                height: fit-content;
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;
            }

            th, td {
                /*text-align: left;*/
                padding: 0px;
            }

            .dataRow:nth-child(even) {
                background-color: #d0d0d0
            }
            table, th, td {
                border-collapse: collapse;
                padding:4px;
            }
            td{
                border:1px solid #ddd;
                border-top: none;
                border-bottom: none;
            }
            .bottom-Border {
                border-bottom:  1px solid #ddd;
            }
            .tableHeaderRow{
                background-color: #F1F7ED;
                font-weight: 700;
            }
            .subHeaderRow{
                font-weight: 500;
                text-align:center;
                /*background-color: #8e8e8e;*/
                color:white;
                padding-top:5px;
                padding-botton:5px;
            }
            .totalsCol{
                color:black;background-color:#f1f7ed;border-bottom: solid 1px #ddd; padding-left:15px;padding-right:15px;text-align: center;
            }
            .totalsRow{
                color: #404040;
                text-align: left;
                border-top: 1px solid #ddd;
                font-weight: 600;
                font-size: 0.95rem;
            }
            .doctorName{
                font-weight: bold;
            }
        </style>
    </div>

    <form class="kt-form filtersPanel bd-callout bd-callout-info sigmaPanel" method="GET" action="{{route('QC-report')}}" style="/*height:30%*/">


        <!-- FILTERS -->
        <div class="container-fluid">
            <div class="row g-3" style="margin-bottom: 20px;">
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-semibold">From Date:</label>
                    <input class="form-control" type="date" name="from" 
                           value="{{ request('from', $from ?? '') }}" 
                           style="font-size: 14px; height: 38px;">
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-semibold">To Date:</label>
                    <input class="form-control" type="date" name="to" 
                           value="{{ request('to', $to ?? '') }}" 
                           style="font-size: 14px; height: 38px;">
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-semibold">Failure Cause:</label>
                    <select class="form-select selectpicker clearOnAll" multiple name="causesInput[]"
                            id="causesInput" data-live-search="true" title="All" data-hide-disabled="true"
                            style="font-size: 14px; height: 38px;">


                                @if ($allCausesSelected)
                                    <option value="all" selected >All</option>
                                    @foreach($allFailureCauses as $d)
                                        <option value="{{$d->id}}" >{{$d->text}}</option>
                                    @endforeach

                                @else
                                    @php $idsOfSelectedCauses = $selectedFailureCauses->pluck('id')->toArray(); @endphp
                                    <option value="all">All</option>
                                    @foreach($allFailureCauses as $d)
                                        <option value="{{$d->id}}" {{ in_array($d->id ,$idsOfSelectedCauses) ? 'selected' : ''}}>{{$d->text}}</option>
                                    @endforeach
                                @endif

                        </select>
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    @if(isset($clients))
                        <label class="form-label fw-semibold">Doctor:</label>
                        <select class="form-select selectpicker clearOnAll" multiple name="doctor[]"
                                id="doctor" data-live-search="true" title="All" data-hide-disabled="true"
                                style="font-size: 14px; height: 38px;">

                                    <option value="all" {{(isset($selectedClients) && $selectedClients== 'all') ? 'selected' : ''}}>
                                        All
                                    </option>
                                    @foreach($clients as $d)
                                        <option value="{{$d->id}}" {{(isset($selectedClients) && in_array($d->id ,$selectedClients)) ? 'selected' : ''}}>{{$d->name}}</option>
                                    @endforeach

                            </select>
                    @endif
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-semibold">Type of failure:</label>
                    <select class="form-select selectpicker clearOnAll" multiple name="failureTypeInput[]"
                            id="failureTypeInput" data-live-search="true" title="All" data-hide-disabled="true"
                            style="font-size: 14px; height: 38px;">

                                <option value="all" {{in_array("all" , $typesSelected) ? 'selected' : ''}}>All</option>
                                <option value="0" {{in_array(0 , $typesSelected) ? 'selected' : ''}} >Reject</option>
                                <option value="1" {{in_array(1 , $typesSelected) ? 'selected' : ''}} >Repeat</option>
                                <option value="2" {{in_array(2 , $typesSelected) ? 'selected' : ''}} >Modification</option>
                                <option value="3" {{in_array(3 , $typesSelected) ? 'selected' : ''}} >Redo</option>

                        </select>
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-semibold">Actions:</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4" style="font-size: 14px; height: 38px;">
                            <i class="fas fa-search me-1"></i>Submit
                        </button>
                        <button type="button" class="btn btn-secondary px-4 printBtn" style="font-size: 14px; height: 38px;">
                            <i class="fas fa-print me-1"></i>Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <div class="sigmaPanel bg-white rounded shadow-sm p-4 mb-4">
        <div class="row g-3 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="fas fa-briefcase text-primary me-2" style="font-size: 24px;"></i>
                            <h5 class="card-title mb-0 fw-bold">Total Cases</h5>
                        </div>
                        <h2 class="display-6 text-success fw-bold mb-1">{{array_sum($amountOfCases) }}</h2>
                        <small class="text-muted">Cases Processed</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="fas fa-cubes text-danger me-2" style="font-size: 24px;"></i>
                            <h5 class="card-title mb-0 fw-bold">Total Units</h5>
                        </div>
                        <h2 class="display-6 text-danger fw-bold mb-1" id="numOfUnitsFailed"></h2>
                        <small class="text-muted">Units Failed</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12">

            <div class=" ">
                <div class="">
                    <p class="text-muted"></p>
                    <div class="" style="overflow-x:auto;">
                        @php

                        $failuresDesc = [0 => "Rejection",1 => "Repeat", 2 => "Modification" , 3=> "Redo"];
                        $counterTest = 0;
                        $monthHasNoFailures = false;
                        @endphp

                        @foreach($selectedMonths as $month)
                            {{$monthHasNoFailures = false;}}
                            @php if ($amountOfCases[$month] == 0)
                            $monthHasNoFailures = true;
                            @endphp
                            <table border="1" class="xl649957 printable sunriseTable" style="border-collapse:collapse;">
                                <thead>
                                <tr class="bottom-Border subHeaderRow" style="mso-height-source:userset;">
                                    <th class="" style="background-color: transparent !important;">Month:</th>
                                    <th colspan="5"
                                        style="height:21.95pt;border-top:none">{{$month}} {{$amountOfCases != 0 ? '('.$amountOfCases[$month] . ") Cases" : ""}}</th>

                                </tr>
                                </thead>


                                <tbody>
                                <!-- The Months row -->

                                @if($monthHasNoFailures)
                                    <tr  style="text-align: center;color:forestgreen"> <td colspan="2" class="xl639957" style="height:21.95pt;border-top:none">No Incidents</td></tr>
                                    @continue

                                @endif

                                <!--The MAIN row -->
                                <tr class=" border-bottom tableHeaderRow">
                                    <td class="xl639957" style="height:21.95pt;border-top:none">Dr Name</td>
                                    <td class="xl639957" style="">Patient</td>
                                    <td class="xl639957" style="">Status</td>
                                    <td class="xl639957" style="">Causes</td>
                                    <td class="xl639957" style=""># of Units</td>
                                    <td class="xl639957" style="">Date Failure Registered</td>
                                </tr>
                                <!-- Client ROWS -->

                                @foreach($failureLogs[$month] as $failLog )
                                    @if(!in_array('all' ,$selectedClients))
                                        @if(isset($selectedClients) && !in_array($failLog->case->client->id ,$selectedClients))
                                            @continue;
                                        @endif
                                    @endif

                                    <!-- if all is selected, dont check if client is selected or not, otherwise check each one by id -->
                                    {{--@if(!in_array('all' ,$selectedClients))--}}
                                    {{--@if(isset($selectedClients) && !in_array($client->id ,$selectedClients))--}}
                                    {{--@continue;--}}
                                    {{--@endif--}}
                                    {{--@endif--}}

                                    <tr class="dataRow" style="">
                                        <td class="xl669957 doctorName">{{$failLog->case->client->name ?? "Case Not found"}}</td>
                                        <td class="xl669957">{{$failLog->case->patient_name ?? "Case Not found"}}</td>
                                        <td class="xl669957">{{$failuresDesc[$failLog->failure_type]}}</td>
                                        <td class="xl669957">{{$failLog->causeObject->text}}</td>

                                        <td class="xl669957">
                                            @php
                                             if(isset($failLog->case) ){
                                                $numOfUnits= $failLog->case->failedUnitsAmount($failLog->failure_type);
                                                $counterTest= $counterTest + $failLog->case->failedUnitsAmount($failLog->failure_type);}
                                            else
                                                $numOfUnits = "Case Not found";

                                            @endphp

                                                {{$numOfUnits}}
                                        </td>
                                        <td class="xl669957">{{substr($failLog->created_at,0,-3)}}</td>
                                    </tr>
                                @endforeach


                                <!-- Totals for whole lab Row -->
                                {{--<tr style="">--}}
                                    {{--<td class="xl669957">Totals</td>--}}

                                    {{--@foreach($labLevelTotal[$month] as $total)--}}
                                        {{--<td class="totalsRow" style="">{{$total}}</td>--}}
                                    {{--@endforeach--}}
                                    {{--<td class="totalsRow" style="">{{array_sum($labLevelTotal[$month])}}</td>--}}
                                {{--</tr>--}}
                                </tbody>
                            </table>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('js')
<script src="{{asset('assets/js/tether.min.js')}}"></script>

<script>
    $(document).ready(function () {
        $(".toggle-group > *").addClass("unstyled");
        $(".toggle").addClass("unstyled");
        $(".toggle-group > label").addClass("toggleInnerBtns");
        $("#numOfUnitsFailed").html({!! $counterTest !!});

        console.log("Amount of units : " );
            console.log({!! $counterTest !!});
    });

    function printData()
    {
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

        var styling=document.getElementById("style");
        newWin= window.open("");
        newWin.document.write(styling.innerHTML);
        newWin.document.write('<h3 style="float:left">Quality Control Report</h3> ' +
            ' <h4 style="float:right"> Date Printed :{!! date("d") !!} - {!! date("M") !!} - {!! date("Y") !!} </h4>');
        $.each(tables, function(key, value) {
            newWin.document.write(value.outerHTML);
        });
        newWin.print();
        newWin.close();
    }
    $('.printBtn').on('click',function(){
        printData();
    })

</script>
@endpush
