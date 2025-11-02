@extends('layouts.app' ,[ 'pageSlug' => 'Job Types Report'])

@push('css')
<style>
/* Report-specific styling - dropdown fixes now in sigma-reports-master.css */

/* Fix placeholder text */
.filter-option-inner {
    color: #6c757d !important;
    font-weight: 500;
}

.bootstrap-select .dropdown-toggle .filter-option .filter-option-inner {
    color: #6c757d !important;
    opacity: 1 !important;
}

.bootstrap-select .dropdown-toggle:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(99, 141, 255, 0.25) !important;
}

.bootstrap-select button.dropdown-toggle .filter-option {
    text-align: left !important;
}

.bootstrap-select button.dropdown-toggle .filter-option .filter-option-inner {
    color: #6c757d !important;
    font-weight: 500 !important;
    opacity: 1 !important;
}

.bootstrap-select {
    width: 100% !important;
}
.bootstrap-select .dropdown-toggle {
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 8px 12px;
    height: auto;
}

.bootstrap-select .dropdown-toggle:hover {
    border-color: #adb5bd;
}

.bootstrap-select .dropdown-menu li a {
    color: #212529 !important;
    padding: 8px 16px;
}

.bootstrap-select .dropdown-menu li.selected a {
    background-color: #e9ecef;
    color: #495057 !important;
}

/* Custom Cases Toggle - Rectangular Style */
#cases-checkbox {
    display: none;
}

.cases-switch {
    position: relative;
    width: 80px;
    height: 40px;
    background-color: rgb(99, 99, 99);
    border-radius: 8px;
    z-index: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgb(126, 126, 126);
    box-shadow: 0px 0px 3px rgb(2, 2, 2) inset;
    color: rgb(48, 48, 48);
    font-weight: 600;
    font-size: 12px;
    margin-right: 8px;
}

#cases-checkbox:checked + .cases-switch {
    box-shadow: 0px 0px 1px rgb(151, 243, 255) inset,
      0px 0px 2px rgb(151, 243, 255) inset, 0px 0px 10px rgb(151, 243, 255) inset,
      0px 0px 40px rgb(151, 243, 255), 0px 0px 100px rgb(151, 243, 255),
      0px 0px 5px rgb(151, 243, 255);
    border: 2px solid rgb(255, 255, 255);
    background-color: rgb(146, 180, 184);
    color: rgb(255, 255, 255);
}

#cases-checkbox:checked + .cases-switch {
    filter: drop-shadow(0px 0px 5px rgb(151, 243, 255));
}

/* Connected Toggle Buttons - Rounded Rectangle Style */
.connected-toggle-container {
    display: inline-flex;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    overflow: hidden;
    background: #fff;
}

.connected-toggle-btn {
    border: none;
    background: #fff;
    padding: 8px 16px;
    font-size: 12px;
    font-weight: 600;
    color: #495057;
    cursor: pointer;
    transition: all 0.2s ease;
    border-right: 1px solid #dee2e6;
    min-width: 50px;
    text-align: center;
}

.connected-toggle-btn:last-child {
    border-right: none;
}

.connected-toggle-btn:first-child {
    border-radius: 5px 0 0 5px;
}

.connected-toggle-btn:last-child {
    border-radius: 0 5px 5px 0;
}

.connected-toggle-btn:hover {
    background: #f8f9fa;
}

.connected-toggle-btn.active {
    background: #B8CDD9;
    color: #2c5766;
    font-weight: 700;
}

.connected-toggle-btn:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(184, 205, 217, 0.3);
}
</style>
@endpush

@section('content')
    <link href="{{ asset('assets/css/sigma-reports-master.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sigma-reports-theme.css') }}" rel="stylesheet">
    <!-- styles to carry on while printing -->


    <div class="report-filters-card">
        <form class="kt-form" method="GET" action="{{route('job-types-report')}}">
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
                        <label><i class="fas fa-briefcase"></i> Job Type:</label>
                        <select class="selectpicker clearOnAll" multiple name="jobTypesInput[]" id="jobTypesInput"
                            data-live-search="true" title="All Job Types" data-hide-disabled="true">
                            @if ($allJobTypesSelected)
                                <option value="all" selected>All</option>
                                @foreach($jobTypes as $d)
                                    <option value="{{$d->id}}">{{$d->name}}</option>
                                @endforeach
                            @else
                                @php $idsOfSelectedJobTypes = $selectedJobTypes->pluck('id')->toArray(); @endphp
                                <option value="all">All</option>
                                @foreach($jobTypes as $d)
                                    <option value="{{$d->id}}" {{(in_array($d->id ,$idsOfSelectedJobTypes)) ? 'selected' : ''}}>{{$d->name}}</option>
                                @endforeach
                            @endif
                        </select>
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
                    <div class="col-lg-2 col-md-4 col-12">
                        <label><i class="fas fa-toggle-on"></i> View Mode:</label>
                        <div class="connected-toggle-container">
                            <button type="button" class="connected-toggle-btn {{ $perUnitTrigger ? 'active' : '' }}" id="units-toggle">
                                <input type="radio" name="perToggle" value="1" {{ $perUnitTrigger ? 'checked' : '' }} style="display: none;" id="units-radio">
                                UNITS
                            </button>
                            <button type="button" class="connected-toggle-btn {{ !$perUnitTrigger ? 'active' : '' }}" id="cases-toggle">
                                <input type="radio" name="perToggle" value="0" {{ !$perUnitTrigger ? 'checked' : '' }} style="display: none;" id="cases-radio">
                                CASES
                            </button>
                        </div>
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
                    <i class="fas fa-print printBtn"></i>
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
                        <!-- Single Combined Table -->
                        <div class="sigma-report-table-container" style="margin-bottom: 1.5rem;">
                            <table class="printable sigma-report-table">
                                <thead>
                                    <tr>
                                        <th class="header-dark" style="color:white !important;">Doctor</th>
                                        @foreach($selectedJobTypes as $jobType)
                                            <th class="text-center header-light" style="color:#408385 !important;">{{$jobType->name}}</th>
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
                                    @endphp

                                    <!-- Client Rows -->
                                    @foreach($filteredClients as $client)
                                        <tr>
                                            <td class="primary-text">{{$client->name}}</td>
                                            @php
                                                $clientTotal = 0;
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
                                                    $clientTotal += $totalForThisJobType;
                                                    $grandTotals[$jobType->id] += $totalForThisJobType;
                                                @endphp
                                                <td class="text-center">{{$totalForThisJobType}}</td>
                                            @endforeach
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
        let formSubmitted = false;

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

            // Prevent double submissions
            if (formSubmitted) {
                console.log('Form already submitted, ignoring click');
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
                formSubmitted = true;
                console.log('Submitting form with perToggle:', isUnits ? '1' : '0');
                form.submit();
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

        var styling=document.getElementById("style");
        newWin= window.open("");
        newWin.document.write(styling.innerHTML);
        newWin.document.write('<h3 style="float:left">Clients Consumptions Report <span style="color:#2b2b2b"> - by Job Type, per '+'{{$perUnitTrigger ? "Unit" : "Case"}}'+'</span></h3> ' +
            ' <h4 style="float:right"> Date Printed :{!! date("d") !!} - {!! date("M") !!} - {!! date("Y") !!} </h4>');
        $.each(tables, function(key, value) {
            newWin.document.write(value.outerHTML);
        });
        newWin.print();
        newWin.close();
    }
    $('.printBtn').on('click',function(){
        printData();
    });

</script>
@endpush
