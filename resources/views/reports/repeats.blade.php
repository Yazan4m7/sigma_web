@extends('layouts.app', ['pageSlug' => isset($perUnitTrigger) ? 'Repeats Report (Per Unit)' : 'Repeats Report (Per Case)'])

@push('css')
<style>
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
    background: #f8f9fa;
    padding: 8px 15px;
    font-size: 12px;
    font-weight: 600;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.25s ease;
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

.connected-toggle-btn:hover:not(.active) {
    background: #e9ecef;
    color: #495057;
}

.connected-toggle-btn.active {
    background: linear-gradient(135deg, #2c5766 0%, #3a7080 100%);
    color: #ffffff;
    font-weight: 700;
    box-shadow: 0 2px 4px rgba(44, 87, 102, 0.3);
    transform: translateY(-1px);
}

.connected-toggle-btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(44, 87, 102, 0.2);
}

/* Disabled state for toggle container */
.connected-toggle-container.disabled {
    opacity: 0.5;
    pointer-events: none;
    cursor: not-allowed;
}

.connected-toggle-container.disabled .connected-toggle-btn {
    cursor: not-allowed;
}

/* Modern iOS-style Toggle Switch */
.modern-toggle-container {
    display: inline-flex;
    align-items: center;
    gap: 12px;
}

.modern-toggle-label {
    font-size: 13px;
    font-weight: 600;
    color: #6c757d;
    transition: color 0.3s ease;
    user-select: none;
}

.modern-toggle-label.active {
    color: #2c5766;
}

.modern-toggle-switch {
    position: relative;
    display: inline-block;
    width: 52px;
    height: 28px;
}

.modern-toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.modern-toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #B8CDD9;
    transition: 0.3s;
    border-radius: 28px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.modern-toggle-slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.modern-toggle-switch input:checked + .modern-toggle-slider {
    background-color: #638DFF;
}

.modern-toggle-switch input:checked + .modern-toggle-slider:before {
    transform: translateX(24px);
}

.modern-toggle-switch:hover .modern-toggle-slider {
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.15), 0 0 0 3px rgba(184, 205, 217, 0.2);
}

.modern-toggle-switch input:checked:hover + .modern-toggle-slider {
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.15), 0 0 0 3px rgba(99, 141, 255, 0.2);
}
</style>
@endpush

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
                        <div class="modern-toggle-container">
                            <span class="modern-toggle-label {{ $countOrPercentage ? 'active' : '' }}">Count</span>
                            <div class="modern-toggle-switch">
                                <input type="checkbox" id="toggle-checkbox" {{ !$countOrPercentage ? 'checked' : '' }}>
                                <label for="toggle-checkbox" class="modern-toggle-slider"></label>
                            </div>
                            <span class="modern-toggle-label {{ !$countOrPercentage ? 'active' : '' }}">%</span>
                            <!-- Hidden input to persist the toggle state -->
                            <input type="hidden" name="countOrPercentageToggle" id="countOrPercentageToggle" value="{{ $countOrPercentage ? '1' : '0' }}">
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
            let isPageLoaded = false;
            let formSubmitted = false;

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

            // Modern toggle switch functionality with label updates
            const toggle = document.getElementById('toggle-checkbox');
            const labels = document.querySelectorAll('.modern-toggle-label');
            const viewModeContainer = document.getElementById('view-mode-container');

            if (toggle) {
                // Update label active states based on toggle position
                function updateLabels() {
                    labels.forEach(label => {
                        if (toggle.checked) {
                            // When checked (percentage mode)
                            if (label.textContent.trim() === '%') {
                                label.classList.add('active');
                            } else {
                                label.classList.remove('active');
                            }
                        } else {
                            // When unchecked (count mode)
                            if (label.textContent.trim() === 'Count') {
                                label.classList.add('active');
                            } else {
                                label.classList.remove('active');
                            }
                        }
                    });
                }

                // Enable/disable view mode toggle based on display mode
                function updateViewModeToggle() {
                    if (toggle.checked) {
                        // Percentage mode - disable view mode toggle
                        viewModeContainer.classList.add('disabled');
                        $('#units-toggle, #cases-toggle').prop('disabled', true);
                        console.log('View mode toggle disabled (percentage mode)');
                    } else {
                        // Count mode - enable view mode toggle
                        viewModeContainer.classList.remove('disabled');
                        $('#units-toggle, #cases-toggle').prop('disabled', false);
                        console.log('View mode toggle enabled (count mode)');
                    }
                }

                // Listen for toggle changes
                toggle.addEventListener('change', function() {
                    // Don't submit if page is still loading
                    if (!isPageLoaded) {
                        console.log('Page still loading, ignoring toggle change');
                        return false;
                    }

                    console.log('Count/Percentage toggle changed:', this.checked ? 'Percentage' : 'Count');

                    // Update label states
                    updateLabels();

                    // Update view mode toggle state
                    updateViewModeToggle();

                    // Update the hidden input field (unchecked = 1/count, checked = 0/percentage)
                    const hiddenInput = document.getElementById('countOrPercentageToggle');
                    hiddenInput.value = this.checked ? '0' : '1';

                    // Submit the main form
                    const form = document.querySelector('.kt-form');
                    if (form) {
                        form.submit();
                    }
                });

                // Initialize label states and view mode toggle on page load
                updateLabels();
                updateViewModeToggle();
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
