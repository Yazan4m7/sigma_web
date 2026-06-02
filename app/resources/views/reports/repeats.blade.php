@extends('layouts.app', ['pageSlug' => isset($perUnitTrigger) ? 'Repeats Report (Per Unit)' : 'Repeats Report (Per Case)'])


@section('content')
    <link href="{{ asset('assets/css/sigma-reports-master.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sigma-reports-theme.css') }}" rel="stylesheet">
    <!-- styles to carry on while printing -->

    <div class="report-filters-card">
        <form class="kt-form" method="GET" action="{{ route('repeats-report') }}">
            <!-- FILTERS -->
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
                    <div class="col-lg-12 col-md-12 col-6  align-items-end justify-content-end ms-auto">
                        <!-- Units/Cases Toggle -->
                        <div class="toggle-cards-container" style="margin-left: auto;">
                            <div class="toggle-card {{ $perUnitTrigger == 'on' ? 'active' : '' }}" id="units-toggle">
                                <input type="radio" name="perToggle" value="1" {{ $perUnitTrigger == 'on' ? 'checked' : '' }} style="display: none;" id="units-radio">
                                <i class="fas fa-cube"></i>
                                <span>UNITS</span>
                            </div>
                            <div class="toggle-card {{ $perUnitTrigger != 'on' ? 'active' : '' }}" id="cases-toggle">
                                <input type="radio" name="perToggle" value="0" {{ $perUnitTrigger != 'on' ? 'checked' : '' }} style="display: none;" id="cases-radio">
                                <i class="fas fa-file-alt"></i>
                                <span>CASES</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        <div class="toggle-switch-container">
                            <span class="toggle-label-left {{ $countOrPercentage ? 'active' : '' }}">Count</span>
                            <div class="toggle-switch" id="display-mode-toggle">
                                <input type="checkbox" id="toggle-checkbox" class="toggle-checkbox" {{ !$countOrPercentage ? 'checked' : '' }}>
                                <label for="toggle-checkbox" class="toggle-slider"></label>
                            </div>
                            <span class="toggle-label-right {{ !$countOrPercentage ? 'active' : '' }}">%</span>
                        </div>
                    </div>
                </div>

                <!-- BUTTONS ROW 2: Actions -->
                <div class="row g-3 align-items-center">
                    <div class="col-lg-4 col-md-4 col-12">
                        <button type="submit" class="btn btn-primary-enhanced" style="height: 50px; padding: 12px 24px; font-size: 16px; font-weight: 600; width: 70%;">
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
                <div class="sigma-table-container">
                    <div style="overflow-x:auto;">
                        <div id="totalsTableHolder"> </div>
                        @foreach ($selectedMonths as $month)
                            @php
                                $labLevelTotal[$month] = array_fill_keys([0, 1, 2, 3, 4], 0);
                                $clientLevelTotal[$month] = array_fill_keys([0, 1, 2, 3, 4], 0);

                            @endphp
                            <div class="sigma-report-table-container" style="margin-bottom: 1.5rem;">
                            <table class="printable sigma-report-table">
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
                                                        $currentTotal = isset($perUnitTrigger)
                                                            ? $client->getFailedUnitsCount($month, $failureTypeId)
                                                            : $client->getFailedCasesCount($month, $failureTypeId);
                                                        $clientLevelTotal[$month][$failureTypeId] += $currentTotal;
                                                        $labLevelTotal[$month][$failureTypeId] += $currentTotal;
                                                        $docTotalUnits += $currentTotal;
                                                    }
                                                    // Percentage mode - show percentages
                                                    else {
                                                        $currentTotal = isset($perUnitTrigger)
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
                            </div>
                        @endforeach
                        <div id="totalsTableTempHolder"></div>
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
            // Toggle functionality for Units/Cases
            $('#units-toggle, #cases-toggle').on('click', function(e) {
                e.preventDefault();

                const isUnits = $(this).attr('id') === 'units-toggle';

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
                    form.submit();
                }
            });

            // Toggle switch functionality
            const toggle = document.getElementById('toggle-checkbox');
            const leftLabel = document.querySelector('.toggle-label-left');
            const rightLabel = document.querySelector('.toggle-label-right');

            if (toggle) {
                toggle.addEventListener('change', function() {
                    // Create a form element to submit the change
                    const form = document.createElement('form');
                    form.method = 'GET';
                    form.action = window.location.pathname;

                    // Add all current form values
                    const currentForm = document.querySelector('.kt-form');
                    const formData = new FormData(currentForm);

                    for (let [key, value] of formData.entries()) {
                        if (key !== 'countOrPercentageToggle') {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            input.value = value;
                            form.appendChild(input);
                        }
                    }

                    // Add the toggle value
                    const toggleInput = document.createElement('input');
                    toggleInput.type = 'hidden';
                    toggleInput.name = 'countOrPercentageToggle';
                    toggleInput.value = this.checked ? '0' : '1';
                    form.appendChild(toggleInput);

                    // Submit the form
                    document.body.appendChild(form);
                    form.submit();
                });
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
