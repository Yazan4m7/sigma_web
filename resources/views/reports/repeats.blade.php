@extends('layouts.app', ['pageSlug' => isset($perUnitTrigger) ? 'Repeats Report (Per Unit)' : 'Repeats Report (Per Case)'])

@section('content')
    <link href="{{ asset('assets/css/sigma-reports-master.css') }}?v={{ filemtime(public_path('assets/css/sigma-reports-master.css')) }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sigma-reports-theme.css') }}?v={{ filemtime(public_path('assets/css/sigma-reports-theme.css')) }}" rel="stylesheet">
    <!-- styles to carry on while printing -->

    <div class="sigma-report-standard">
    <div class="report-filters-card">
        <form class="kt-form" method="GET" action="{{ route('repeats-report') }}">
            <!-- FILTERS -->
            <div class="container-fluid">
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-lg-2 col-md-4 col-6">
                        <label for="repeats_from"><i class="fas fa-calendar-alt"></i> From Date:</label>
                        <x-ios-dtp
                            name="from"
                            id="repeats_from"
                            :value="request('from', now()->startOfMonth()->format('Y-m-d'))"
                            mode="date"
                            :required="true"
                        />
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <label for="repeats_to"><i class="fas fa-calendar-alt"></i> To Date:</label>
                        <x-ios-dtp
                            name="to"
                            id="repeats_to"
                            :value="request('to', now()->endOfMonth()->format('Y-m-d'))"
                            mode="date"
                            :required="true"
                        />
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        <label><i class="fas fa-exclamation-circle"></i> Status Types:</label>
                        <select class="selectpicker clearOnAll" multiple name="failureTypeInput[]"
                            id="failureTypeInput" data-live-search="true" title="All Status Types" data-hide-disabled="true">

                        @php

                        @endphp
                        @if ($allFailureTypesSelected)
                            <option value="all" selected>All</option>

                            <option value="0">Reject</option>
                            <option value="1">Repeat</option>
                            <option value="2">Modification</option>
                            <option value="3">Redo</option>
                            <option value="4">Successful</option>
                        @else
                            <option value="all">All</option>
                            <option value="0" {{ in_array(0, $selectedFailureTypes) ? 'selected' : '' }}>Reject
                            </option>
                            <option value="1" {{ in_array(1, $selectedFailureTypes) ? 'selected' : '' }}>Repeat
                            </option>
                            <option value="2" {{ in_array(2, $selectedFailureTypes) ? 'selected' : '' }}>Modification
                            </option>
                            <option value="3" {{ in_array(3, $selectedFailureTypes) ? 'selected' : '' }}>Redo</option>
                            <option value="4" {{ in_array(4, $selectedFailureTypes) ? 'selected' : '' }}>Successful
                            </option>
                        @endif

                    </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        @if (isset($clients))
                            <label><i class="fas fa-user-md"></i> Doctors:</label>
                            <select class="selectpicker clearOnAll" multiple name="doctor[]" id="doctor"
                            data-live-search="true" title="All Doctors" data-hide-disabled="true">

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
                        <label><i class="fas fa-toggle-on"></i> View Mode:</label>
                        <div class="connected-toggle-container" id="view-mode-container">
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
                    <div class="col-lg-2 col-md-4 col-12">
                        <label><i class="fas fa-chart-bar"></i> Display:</label>
                        <div class="connected-toggle-container" id="display-mode-container">
                            <button type="button" class="connected-toggle-btn {{ $countOrPercentage ? 'active' : '' }}" id="count-toggle">
                                COUNT
                            </button>
                            <button type="button" class="connected-toggle-btn {{ !$countOrPercentage ? 'active' : '' }}" id="percent-toggle">
                                %
                            </button>
                        </div>
                        <input type="hidden" name="countOrPercentageToggle" id="countOrPercentageToggle" value="{{ $countOrPercentage ? '1' : '0' }}">
                    </div>
                </div>

                <!-- BUTTONS ROW 2: Actions -->
                <div class="row g-3 align-items-center">
                    <div class="col-lg-4 col-md-4 col-12">
                        <button type="submit" class="btn btn-primary-enhanced">
                            <i class="fas fa-chart-line me-2"></i>   &nbsp;   Generate Report
                        </button>
                    </div>
                    <div class="col-lg-8 col-md-8 col-12 d-flex justify-content-end">

                            <i class="fas fa-print me-1"></i>

                    </div>
                </div>
                </div>
        </form>
    </div>



    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div>
                    <div style="overflow-x:auto;">
                        <div id="totalsTableHolder"> </div>
                        @foreach ($selectedMonths as $month)
                            @continue(!$loop->first)
                            @php
                                $labLevelTotal[$month] = array_fill_keys([0, 1, 2, 3, 4], 0);
                                $clientLevelTotal[$month] = array_fill_keys([0, 1, 2, 3, 4], 0);

                            @endphp
                            <table class="printable sigma-report-table table-plain">
                                <thead>
                                    <tr>
                                        <th class="header-dark" style="color:white !important; ">Doctor Name</th>

                                        @if ($allFailureTypesSelected)
                                            <th class="text-center header-light">Reject</th>
                                            <th class="text-center header-light">Repeat</th>
                                            <th class="text-center header-light">Modification</th>
                                            <th class="text-center header-light">Redo</th>
                                            <th class="text-center header-light" style="= border-radius: 2px 14px 3px 3px;">Successful</th>
                                            @if ($countOrPercentage)
                                                <th class="text-center header-dark" style="color:white !important;    border-radius: 2px 14px 3px 3px;"  >Total</th>
                                            @endif
                                        @else
                                            @if (in_array(0, $selectedFailureTypes))
                                                <th class="text-center header-light">Reject</th>
                                            @endif
                                            @if (in_array(1, $selectedFailureTypes))
                                                <th class="text-center header-light">Repeat</th>
                                            @endif
                                            @if (in_array(2, $selectedFailureTypes))
                                                <th class="text-center header-light">Modification</th>
                                            @endif
                                            @if (in_array(3, $selectedFailureTypes))
                                                <th class="text-center header-light">Redo</th>
                                            @endif
                                            @if (in_array(4, $selectedFailureTypes))
                                                <th class="text-center header-dark">Successful</th>
                                            @endif
                                            @if ($countOrPercentage)
                                                <th class="text-center header-dark" style="color:white !important;    border-radius: 2px 14px 3px 3px;">Total</th>
                                            @endif
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        if (!in_array('all', $selectedClients)) {
                                            $filteredClients = $clients->filter(function ($value, $key) use (
                                                $selectedClients,
                                            ) {
                                                return in_array($key, $selectedClients);
                                            });
                                        } else {
                                            $filteredClients = $clients;
                                        }

                                    @endphp
                                    <!-- Client ROWS -->

                                    @foreach ($filteredClients as $client)
                                        <!-- if all is selected, dont check if client is selected or not, otherwise check each one by id -->
                                        {{-- @if (!in_array('all', $selectedClients)) --}}
                                        {{-- @if (isset($selectedClients) && !in_array($client->id, $selectedClients)) --}}
                                        {{-- @continue; --}}
                                        {{-- @endif --}}
                                        {{-- @endif --}}

                                        <tr>
                                            <td class="primary-text">{{ $client->name }}</td>
                                            @php
                                                $docTotalUnits = 0;
                                                $currentTotal = 0;
                                            @endphp

                                            @foreach ($selectedFailureTypes as $failureTypeId => $failureDescription)
                                                @php
                                                    // Count mode - show actual numbers
                                                    if ($countOrPercentage) {
                                                        $currentTotal = $perUnitTrigger
                                                            ? $client->getFailedUnitsCount($month, $failureTypeId)
                                                            : $client->getFailedCasesCount($month, $failureTypeId);
                                                        $clientLevelTotal[$month][$failureTypeId] += $currentTotal;
                                                        $labLevelTotal[$month][$failureTypeId] += $currentTotal;
                                                        $docTotalUnits += $currentTotal;
                                                    }
                                                    // Percentage mode - show percentages
                                                    else {
                                                        $currentTotal = $perUnitTrigger
                                                            ? $client->getFailedUnitsPercentage(
                                                                    $month,
                                                                    $failureTypeId,
                                                                ) . '%'
                                                            : $client->getFailedCasesPercentage(
                                                                    $month,
                                                                    $failureTypeId,
                                                                ) . '%';
                                                    }
                                                @endphp

                                                <td class="text-center">{{ $currentTotal }}</td>
                                            @endforeach
                                            @if ($countOrPercentage)
                                                <td class="text-center"><strong>{{ $docTotalUnits }}</strong></td>
                                            @endif
                                        </tr>
                                    @endforeach

                                    @if ($countOrPercentage)
                                        <!-- Totals for whole lab Row -->
                                        <tr class="totals-row">
                                            <td><strong>Totals</strong></td>

                                            <!-- if Not all types selected, then check if type exists in selected types array if so print it -->
                                            @foreach ($labLevelTotal[$month] as $key => $total)
                                                @if (!$allFailureTypesSelected)
                                                    @if (in_array($key, $selectedFailureTypes))
                                                        <td class="text-center"><strong>{{ $total }}</strong></td>
                                                    @endif
                                                @else
                                                    <td class="text-center"><strong>{{ $total }}</strong></td>
                                                @endif
                                            @endforeach
                                            <td class="text-center"><strong>{{ array_sum($labLevelTotal[$month]) }}</strong></td>
                                        </tr>
                                    @endif
                                </tbody>

                            </table>
                        @endforeach
                        <div id="totalsTableTempHolder"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/tether.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            let isPageLoaded = false;

            console.log('Repeats Report: Page loading started');

            // Prevent automatic submissions during page load
            setTimeout(function() {
                isPageLoaded = true;
                console.log('Repeats Report: Page load completed, interactions enabled');
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
                    form.submit();
                }
            });

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

            // Display mode toggle (Count / %)
            const countToggle = document.getElementById('count-toggle');
            const percentToggle = document.getElementById('percent-toggle');
            const viewModeContainer = document.getElementById('view-mode-container');
            const displayContainer = document.getElementById('display-mode-container');
            const hiddenInput = document.getElementById('countOrPercentageToggle');

            function setDisplayMode(isCount, submitForm) {
                if (isCount) {
                    countToggle.classList.add('active');
                    percentToggle.classList.remove('active');
                    hiddenInput.value = '1';
                    viewModeContainer.classList.remove('disabled');
                    $('#units-toggle, #cases-toggle').prop('disabled', false);
                    console.log('Display mode: Count');
                } else {
                    percentToggle.classList.add('active');
                    countToggle.classList.remove('active');
                    hiddenInput.value = '0';
                    viewModeContainer.classList.add('disabled');
                    $('#units-toggle, #cases-toggle').prop('disabled', true);
                    console.log('Display mode: Percentage');
                }

                if (submitForm && isPageLoaded) {
                    const form = document.querySelector('.kt-form');
                    if (form) {
                        form.submit();
                    }
                }
            }

            if (countToggle && percentToggle && hiddenInput && displayContainer) {
                countToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!isPageLoaded) {
                        console.log('Page still loading, ignoring display toggle');
                        return false;
                    }
                    setDisplayMode(true, true);
                });

                percentToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!isPageLoaded) {
                        console.log('Page still loading, ignoring display toggle');
                        return false;
                    }
                    setDisplayMode(false, true);
                });

                setDisplayMode(hiddenInput.value === '1', false);
            }
        });

        function printData() {
            var tables = $('.printable');

            var styling = document.getElementById("style");
            newWin = window.open("");
            newWin.document.write(styling.innerHTML);
            newWin.document.write(
                '<h3 style="float:left">Cases Repeat Report <span style="color:#2b2b2b"> - by Repeat, per ' +
                '{{ $perUnitTrigger ? 'Unit' : 'Case' }}' + '</span></h3> ' +
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
        });
    </script>
@endpush
