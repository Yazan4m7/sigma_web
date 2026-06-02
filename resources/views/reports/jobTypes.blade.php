@extends('layouts.app' ,[ 'pageSlug' => 'Job Types Report'])

@section('content')
    <link href="{{ asset('assets/css/sigma-reports-master.css') }}?v={{ filemtime(public_path('assets/css/sigma-reports-master.css')) }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sigma-reports-theme.css') }}?v={{ filemtime(public_path('assets/css/sigma-reports-theme.css')) }}" rel="stylesheet">
    <!-- styles to carry on while printing -->

    <div class="sigma-report-standard">
    <div class="report-filters-card">
        <form class="kt-form" method="GET" action="{{route('job-types-report')}}">
            <!-- FILTERS ROW 1: Main Filters -->
            <div class="container-fluid">
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-lg-2 col-md-4 col-6">
                        <x-report-datetimepicker
                            name="from"
                            id="jobtypes_from"
                            label="From Date:"
                            :value="request('from', now()->startOfMonth()->format('Y-m-d'))"
                            mode="date"
                            :required="true"
                        />
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <x-report-datetimepicker
                            name="to"
                            id="jobtypes_to"
                            label="To Date:"
                            :value="request('to', now()->endOfMonth()->format('Y-m-d'))"
                            mode="date"
                            :required="true"
                        />
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        @php
                            $jobTypeOptions = $jobTypes
                                ->map(fn($jobType) => ['value' => $jobType->id, 'label' => $jobType->name])
                                ->values()
                                ->all();
                            $selectedJobTypeIds = $selectedJobTypes->pluck('id')->toArray();
                        @endphp
                        <x-report-dropdown
                            name="jobTypesInput[]"
                            id="jobTypesInput"
                            label="Job Type:"
                            :options="$jobTypeOptions"
                            :selected="$selectedJobTypeIds"
                            :allSelected="$allJobTypesSelected"
                            title="All Job Types"
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
                                label="Doctors:"
                                :options="$doctorOptions"
                                :selected="$selectedClients ?? null"
                                :allSelected="in_array('all', (array) ($selectedClients ?? []), true)"
                                title="All"
                            />
                        @endif
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        @php
                            $viewModeOptions = [
                                ['label' => 'UNITS', 'value' => '1', 'id' => 'units'],
                                ['label' => 'CASES', 'value' => '0', 'id' => 'cases'],
                            ];
                        @endphp
                        <x-report-toggle
                            name="perToggle"
                            label="View Mode:"
                            :options="$viewModeOptions"
                            :selected="$perUnitTrigger ? '1' : '0'"
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
                    <div class="col-lg-8 col-md-8 col-12 d-flex justify-content-end gap-2">
                    <i class="fas fa-print printBtn"></i>
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
                            <!-- Single Combined Table -->
                            <table class="printable sigma-report-table">
                                <thead>
                                    <tr>
                                        <th class="header-dark" style="color:white !important;">Doctor</th>
                                        @foreach($selectedJobTypes as $jobType)
                                            <th class="text-center header-light" style="">{{$jobType->name}}</th>
                                        @endforeach
                                        <th class="text-center header-dark" style="color:white !important; border-radius: 2px 14px 3px 3px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Filter clients if needed
                                        if(!in_array('all', $selectedClients)) {
                                            $filteredClients = $clients->filter(function ($value, $key) use ($selectedClients) {
                                                return in_array($key, $selectedClients);
                                            });
                                        } else {
                                            $filteredClients = $clients;
                                        }

                                        // Initialize totals arrays
                                        $grandTotals = [];
                                        foreach($selectedJobTypes as $jobType) {
                                            $grandTotals[$jobType->id] = 0;
                                        }
                                        $overallTotal = 0;
                                        $selectedJobTypeIds = $selectedJobTypes->pluck('id')->toArray();
                                    @endphp

                                    <!-- Client Rows -->
                                    @foreach($filteredClients as $client)
                                        <tr>
                                            <td class="primary-text">{{$client->name}}</td>
                                            @php
                                                $clientTotal = 0;
                                                $clientCaseIds = [];
                                            @endphp
                                            @foreach($selectedJobTypes as $jobType)
                                                @php
                                                    // Calculate total across all selected months for this client and job type
                                                    $totalForThisJobType = 0;
                                                    foreach($selectedMonths as $month) {
                                                        if ($perUnitTrigger) {
                                                            $totalForThisJobType += $client->numOfUnitsByJobType($jobType->id, $month);
                                                        } else {
                                                            $totalForThisJobType += $client->numOfCasesByJobType($jobType->id, $month);
                                                        }
                                                    }
                                                    if ($perUnitTrigger) {
                                                        $clientTotal += $totalForThisJobType;
                                                    }
                                                    $grandTotals[$jobType->id] += $totalForThisJobType;
                                                @endphp
                                                <td class="text-center">{{$totalForThisJobType}}</td>
                                            @endforeach
                                            @php
                                                if (!$perUnitTrigger) {
                                                    foreach($selectedMonths as $month) {
                                                        $clientCaseIds = array_merge(
                                                            $clientCaseIds,
                                                            $client->caseIdsByJobTypes($selectedJobTypeIds, $month)->toArray()
                                                        );
                                                    }
                                                    $clientTotal = count(array_unique($clientCaseIds));
                                                }
                                            @endphp
                                            <td class="text-center"><strong>{{$clientTotal}}</strong></td>
                                            @php $overallTotal += $clientTotal; @endphp
                                        </tr>
                                    @endforeach

                                    <!-- Grand Totals Row -->
                                    <tr class="totals-row">
                                        <td><strong>Grand Totals</strong></td>
                                        @foreach($selectedJobTypes as $jobType)
                                            <td class="text-center"><strong>{{$grandTotals[$jobType->id]}}</strong></td>
                                        @endforeach
                                        <td class="text-center"><strong>{{$overallTotal}}</strong></td>
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
<script src="{{asset('assets/js/tether.min.js')}}"></script>
●


<script>
    $(document).ready(function () {
        let isPageLoaded = false;

        console.log('Job Types Report: Page loading started');

        // Prevent automatic submissions during page load
        setTimeout(function() {
            isPageLoaded = true;
            console.log('Job Types Report: Page load completed, interactions enabled');
        }, 1000);

        // Toggle functionality for Units/Cases
        $('#units-toggle, #cases-toggle').on('click', function(e) {
            e.preventDefault();
            console.log('Toggle clicked:', $(this).attr('id'));

            // Don't submit if page is still loading
            if (!isPageLoaded) {
                console.log('Page still loading, ignoring click');
                return false;
            }

            const isUnits = $(this).attr('id') === 'units-toggle';
            console.log('Switching to:', isUnits ? 'Units' : 'Cases');

            // Update visual state
            if (isUnits) {
                $('#units-radio').prop('checked', true);
                $('#units-toggle').addClass('active');
                $('#cases-toggle').removeClass('active');
            } else {
                $('#cases-radio').prop('checked', true);
                $('#cases-toggle').addClass('active');
                $('#units-toggle').removeClass('active');
            }

            // Get current form data and submit
            const form = $('.kt-form')[0];
            if (form) {
                console.log('Submitting form with perToggle:', isUnits ? '1' : '0');
                window.requestAnimationFrame(function() {
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                });
            }
        });

        // No longer needed - we now have a single combined table

        // Initialize selectpicker
        $('.selectpicker').selectpicker();

        // Prevent any automatic form submissions during initialization
        $('.kt-form').on('submit', function(e) {
            console.log('Form submit event triggered, isPageLoaded:', isPageLoaded);
            if (!isPageLoaded) {
                console.log('Preventing form submission during page load');
                e.preventDefault();
                return false;
            }
            console.log('Allowing form submission');
        });
    });

    function initializeModernFilters() {

    }

    function printData()
    {
        var tables = $('.printable');
        if (tables.length === 0) {
            return;
        }

        var headContent = Array.from(document.querySelectorAll('link[rel="stylesheet"], style'))
            .map(function(node) { return node.outerHTML; })
            .join('');

        var newWin = window.open("", "_blank", "width=1200,height=800");
        if (!newWin) {
            return;
        }

        newWin.document.write('<!DOCTYPE html><html><head><title>Job Types Report</title>');
        newWin.document.write(headContent);
        newWin.document.write('</head><body>');
        newWin.document.write('<div style="margin-bottom: 30px;">' +
            '<h3 style="float:left">Clients Consumptions Report <span style="color:#2b2b2b"> - by Job Type, per {{ $perUnitTrigger ? "Unit" : "Case" }}</span></h3>' +
            '<h4 style="float:right"> Date Printed :{!! date("d") !!} - {!! date("M") !!} - {!! date("Y") !!} </h4>' +
            '<div style="clear: both;"></div>' +
            '</div>');
        $.each(tables, function(key, value) {
            newWin.document.write(value.outerHTML);
        });
        newWin.document.write('</body></html>');
        newWin.document.close();
        setTimeout(function() {
            newWin.print();
            newWin.close();
        }, 250);
    }
    $('.printBtn').on('click',function(){
        printData();
    });

</script>
@endpush
