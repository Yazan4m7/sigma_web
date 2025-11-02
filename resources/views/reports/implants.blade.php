@extends('layouts.app', ['pageSlug' => 'Implants Report'])

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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- All styles now centralized in sigma-reports-master.css -->


    <div class="report-filters-card">
        <form class="kt-form" method="GET" action="{{ route('implants-report') }}">
            <!-- FILTERS ROW 1: Main Filters -->
            <div class="container-fluid">
                <div class="row g-3 align-items-end mb-3">

                    <div class="col-lg-2 col-md-3 col-6">
                        <label><i class="fas fa-calendar-alt"></i> To Date:</label>
                        <input class="form-control" type="date" name="to" value="{{request('to', now()->endOfMonth()->format('Y-m-d'))}}">
                    </div>
                    <div class="col-lg-2 col-md-3 col-6">
                        <label><i class="fas fa-calendar-alt"></i> To Date:</label>
                        <input class="form-control" type="date" name="to" value="{{request('to', now()->endOfMonth()->format('Y-m-d'))}}">
                    </div>
                    <div class="col-lg-2 col-md-3 col-6">
                        <label><i class="fas fa-tooth"></i> Implants:</label>
                        <select class="selectpicker clearOnAll" multiple name="implantsInput[]" id="implantsInput"
                            data-live-search="true" title="All Implants" data-hide-disabled="true">
                        @if ($allImplantsSelected)
                            <option value="all" selected>All</option>
                            @foreach ($implants as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        @else
                            @php $idsOfImplantsSelected = $selectedImplants->pluck('id')->toArray(); @endphp
                            <option value="all">All</option>
                            @foreach ($implants as $d)
                                <option value="{{ $d->id }}"
                                    {{ in_array($d->id, $idsOfImplantsSelected) ? 'selected' : '' }}>{{ $d->name }}
                                </option>
                            @endforeach
                        @endif
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6">
                        <label><i class="fas fa-cog"></i> Abutments:</label>
                        <select class="selectpicker clearOnAll" multiple name="abutmentsInput[]" id="abutmentsInput"
                            data-live-search="true" title="All Abutments" data-hide-disabled="true">
                        @if ($allAbutmentsSelected)
                            <option value="all" selected>All</option>
                            @foreach ($abutments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        @else
                            @php $idsOfAbutmentsSelected = $selectedAbutments->pluck('id')->toArray(); @endphp
                            <option value="all">All</option>
                            @foreach ($abutments as $d)
                                <option value="{{ $d->id }}"
                                    {{ in_array($d->id, $idsOfAbutmentsSelected) ? 'selected' : '' }}>
                                    {{ $d->name }}</option>
                            @endforeach
                        @endif
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6">
                        @if (isset($clients) && count($clients) > 0)
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
                        @else
                            <label><i class="fas fa-user-md"></i> Doctors:</label>
                            <select class="selectpicker clearOnAll" multiple name="doctor[]" id="doctor"
                                data-live-search="true" title="No Doctors Available" data-hide-disabled="true" disabled>
                                <option value="">No doctors found in database</option>
                            </select>
                        @endif
                    </div>
                    <div class="col-lg-2 col-md-4 col-12">
                        <label><i class="fas fa-toggle-on"></i> View Mode:</label>
                        <div class="connected-toggle-container">
                            <button type="button" class="connected-toggle-btn {{ $perUnitTrigger == 'on' ? 'active' : '' }}" id="units-toggle">
                                <input type="radio" name="perToggle" value="1" {{ $perUnitTrigger == 'on' ? 'checked' : '' }} style="display: none;" id="units-radio">
                                UNITS
                            </button>
                            <button type="button" class="connected-toggle-btn {{ $perUnitTrigger != 'on' ? 'active' : '' }}" id="cases-toggle">
                                <input type="radio" name="perToggle" value="0" {{ $perUnitTrigger != 'on' ? 'checked' : '' }} style="display: none;" id="cases-radio">
                                CASES
                            </button>
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
                    <!-- Column Visibility Controls (Hidden) -->
                    <div style="display:none;">
                        <div class="columns-dropdown" id="columns-dropdown">
                            <div class="column-checkbox-item disabled">
                                <input type="checkbox" id="col-doctor" checked disabled>
                                <label for="col-doctor">Doctor Name</label>
                            </div>
                            @foreach ($selectedAbutments as $index => $abutment)
                            <div class="column-checkbox-item">
                                <input type="checkbox" id="col-abutment-{{$index}}" checked>
                                <label for="col-abutment-{{$index}}">{{ $abutment->name }}</label>
                            </div>
                            @endforeach
                            <div class="column-checkbox-item disabled">
                                <input type="checkbox" id="col-total" checked disabled>
                                <label for="col-total">Total</label>
                            </div>
                        </div>
                    </div>

                    <div style="overflow-x:auto;">
                        <!-- Main Totals Table -->
                        <div id="totals-table">

                            <div class="sigma-report-table-container">
                                <table class="sigma-report-table printable">
                                    <thead>
                                        <!--The MAIN header row -->
                                        <tr>
                                            <th class=" header-dark" style="color:white !important; ">Doctor Name</th>
                                            @foreach ($selectedAbutments as $d)
                                                <th class="text-center header-light">{{ $d->name }}</th>
                                            @endforeach
                                            <th class="text-center sigma-col-total header-dark" style="color:white !important;    border-radius: 2px 14px 3px 3px;">Total</th>
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
                                            <tr>
                                                <td class="primary-text">{{ $client->name }}</td>
                                                @php
                                                    $docTotalUnits = 0;
                                                    $currentTotal = 0;
                                                    $implantsIds = $selectedImplants->pluck('id')->toArray();
                                                @endphp
                                                @foreach ($selectedAbutments as $abutment)
                                                    @php
                                                        $currentTotal = $totals[$client->id][$abutment->id];
                                                        $docTotalUnits += $currentTotal;

                                                        // Color coding logic with badge system
                                                        $colorClass = '';
                                                        $badgeClass = '';
                                                        if ($currentTotal >= 3) {
                                                            $colorClass = 'usage-high';
                                                            $badgeClass = 'sigma-data-badge-high';
                                                        } elseif ($currentTotal >= 1) {
                                                            $colorClass = 'usage-medium';
                                                            $badgeClass = 'sigma-data-badge-medium';
                                                        } else {
                                                            $colorClass = 'usage-low';
                                                            $badgeClass = 'sigma-data-badge-low';
                                                        }
                                                    @endphp
                                                    <td class="text-center {{ $colorClass }}">
                                                        @if($currentTotal > 0)
                                                            <span class="sigma-data-badge {{ $badgeClass }}">{{ $currentTotal }}</span>
                                                        @else
                                                            {{ $currentTotal }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="text-center"><strong>{{ $docTotalUnits }}</strong></td>
                                            </tr>
                                        @endforeach

                                        <!-- Totals for whole lab Row -->
                                        <tr class="totals-row">
                                            <td class="primary-text">Totals</td>
                                            @foreach ($totals2 as $total)
                                                <td class="text-center"><strong>{{ $total }}</strong></td>
                                            @endforeach
                                            <td class="text-center"><strong>{{ array_sum($totals2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- Pagination matching reference design -->
                                <div class="sigma-report-pagination">
                                    <div class="sigma-rows-per-page">
                                        <span>Rows per page:</span>
                                        <select>
                                            <option>10</option>
                                            <option>25</option>
                                            <option>50</option>
                                        </select>
                                    </div>
                                    <div class="sigma-pagination-nav">
                                        <span class="sigma-pagination-info">1-{{ count($filteredClients) }} of {{ count($filteredClients) }}</span>
                                        <button class="sigma-pagination-btn disabled" disabled>‹</button>
                                        <span class="sigma-pagination-info">1/1</span>
                                        <button class="sigma-pagination-btn disabled" disabled>›</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Breakdown Tables (hidden by default) -->
                        <div id="monthly-breakdown-tables" style="display: none;">
                            @foreach ($selectedMonths as $month)
                                <div class="monthly-table-container" data-month="{{ $month }}">
                                    <div class="monthly-table-header" onclick="toggleMonthlyTable('{{ $month }}')">
                                        <h4>{{ date('F Y', strtotime($month)) }}</h4>
                                        <div class="monthly-expand-icon">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    <div class="monthly-table-content">
                                        <div class="monthly-table-wrapper">
                                            <table class="sigma-report-table printable">
                                        <thead>
                                            <tr>
                                                <th class="sigma-col-customer header-dark" style="color:white !important;" >Doctor Name</th>
                                                @foreach ($selectedAbutments as $d)
                                                    <th class="text-center header-light">{{ $d->name }}</th>
                                                @endforeach
                                                <th class="text-center header-dark" style="color:white !important;    border-radius: 2px 14px 3px 3px;" >Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($filteredClients as $client)
                                                <tr>
                                                    <td class="primary-text">{{ $client->name }}</td>
                                                    @php
                                                        $docTotalUnits = 0;
                                                        $implantsIds = $selectedImplants->pluck('id')->toArray();
                                                    @endphp
                                                    @foreach ($selectedAbutments as $abutment)
                                                        @php
                                                            $currentTotal = $perUnitTrigger
                                                                ? $client->numOfCasesBy_abutment_implants(
                                                                    $abutment->id,
                                                                    $implantsIds,
                                                                    $month,
                                                                )
                                                                : $client->numOfUnitsBy_abutment_implants(
                                                                    $abutment->id,
                                                                    $implantsIds,
                                                                    $month,
                                                                );
                                                            $docTotalUnits += $currentTotal;

                                                            // Color coding logic with badge system
                                                            $colorClass = '';
                                                            $badgeClass = '';
                                                            if ($currentTotal >= 3) {
                                                                $colorClass = 'usage-high';
                                                                $badgeClass = 'sigma-data-badge-high';
                                                            } elseif ($currentTotal >= 1) {
                                                                $colorClass = 'usage-medium';
                                                                $badgeClass = 'sigma-data-badge-medium';
                                                            } else {
                                                                $colorClass = 'usage-low';
                                                                $badgeClass = 'sigma-data-badge-low';
                                                            }
                                                        @endphp
                                                        <td class="text-center {{ $colorClass }}">
                                                            @if($currentTotal > 0)
                                                                <span class="sigma-data-badge {{ $badgeClass }}">{{ $currentTotal }}</span>
                                                            @else
                                                                {{ $currentTotal }}
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    <td class="text-center"><strong>{{ $docTotalUnits }}</strong></td>
                                                </tr>
                                            @endforeach
                                            <!-- Monthly totals row -->
                                            <tr class="table-totals-row">
                                                <td class="table-totals-row-label">Totals</td>
                                                @foreach ($labLevelTotal[$month] as $total)
                                                    <td class="table-cell table-number">{{ $total }}</td>
                                                @endforeach
                                                <td class="table-cell table-number"><b>{{ array_sum($labLevelTotal[$month]) }}</b></td>
                                            </tr>
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
        // Wrap everything in IIFE to avoid conflicts and ensure jQuery is available
        (function($) {
            'use strict';

            // jQuery is now loaded in the header, so we don't need to check anymore
            // Continue directly with initialization

            // Initialize functionality


        // Initialize Arabic text detection
        function detectArabicText() {
            // const arabicRegex = /[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\uFB50-\uFDFF\uFE70-\uFEFF]/;

            // $('.sigma-report-table td, .sigma-report-table th').each(function() {
            //     const text = $(this).text();
            //     if (arabicRegex.test(text)) {
            //         $(this).addClass('arabic-text');
            //     }
            // });
        }

        // Ensure jQuery and DOM are ready before executing
        $(document).ready(function() {
            let isPageLoaded = false;
            let formSubmitted = false;

            console.log('Implants Report: Page loading started');

            // Prevent automatic submissions during page load
            setTimeout(function() {
                isPageLoaded = true;
                console.log('Implants Report: Page load completed, interactions enabled');
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

            $(".toggle-group > *").addClass("unstyled");
            $(".toggle").addClass("unstyled");
            $(".toggle-group > label").addClass("toggleInnerBtns");

            // Initialize Arabic font detection
            detectArabicText();

            // Monthly breakdown toggle functionality with sessionStorage persistence
            let monthlyVisible = sessionStorage.getItem('monthlyBreakdownVisible') === 'true';

            // Set initial state based on sessionStorage
            if (monthlyVisible) {
                $('#monthly-breakdown-tables').show();
                $('#monthly-breakdown-toggle')
                    .removeClass('btn-outline-secondary').addClass('btn-success')
                    .html('<i class="fas fa-eye-slash"></i>')
                    .attr('title', 'Hide Monthly Breakdown');
            }

            $('#monthly-breakdown-toggle').on('click', function() {
                const $button = $(this);
                const $monthlyTables = $('#monthly-breakdown-tables');

                if (monthlyVisible) {
                    // Hide monthly tables
                    $monthlyTables.slideUp(300);
                    $button.removeClass('btn-success').addClass('btn-outline-secondary')
                           .html('<i class="fas fa-eye"></i>')
                           .attr('title', 'Show Monthly Breakdown');
                    monthlyVisible = false;
                    sessionStorage.setItem('monthlyBreakdownVisible', 'false');
                } else {
                    // Show monthly tables
                    $monthlyTables.slideDown(300);
                    $button.removeClass('btn-outline-secondary').addClass('btn-success')
                           .html('<i class="fas fa-eye-slash"></i>')
                           .attr('title', 'Hide Monthly Breakdown');
                    monthlyVisible = true;
                    sessionStorage.setItem('monthlyBreakdownVisible', 'true');
                }
            });

            // Column visibility controls functionality
            initializeColumnVisibility();

            // Print button event handler
            $('.printBtn').on('click', function() {
                printData();
            });
        });

        function printData() {
            // Check if printable tables exist
            var tables = $('.printable');
            if (tables.length === 0) {
                console.error('No printable tables found on the page.');
                alert('No printable content found. Please ensure the report is loaded.');
                return;
            }

            // Try to get styling element with null checking
            var styling = document.getElementById("style");
            var stylingContent = '';
            if (styling && styling.innerHTML) {
                stylingContent = styling.innerHTML;
            } else {
                // Fallback CSS for printing if no style element found
                stylingContent = `
                    <style>
                        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                        .printable { font-family: Arial, sans-serif; font-size: 12px; }
                        body { font-family: Arial, sans-serif; margin: 20px; }
                    </style>
                `;
            }

            // Create new window with complete HTML structure
            var newWin = window.open("", "_blank", "width=1200,height=800");
            if (!newWin) {
                console.error('Could not open print window. Please check popup blockers.');
                alert('Could not open print window. Please check popup blockers.');
                return;
            }

            // Write complete HTML document
            newWin.document.write('<!DOCTYPE html><html><head><title>Implants Report</title>');
            newWin.document.write(stylingContent);
            newWin.document.write('</head><body>');
            newWin.document.write(
                '<div style="margin-bottom: 30px;">' +
                '<h3 style="float:left; font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif; font-size: 31.2px;">Clients Consumptions Report <span style="color:#2b2b2b"> - by Abutments & Implants, per ' +
                '{{ $perUnitTrigger ? "Unit" : "Case" }}' + '</span></h3>' +
                '<h4 style="float:right; font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif; font-size: 24.7px;"> Date Printed: {{ date("d") }} - {{ date("M") }} - {{ date("Y") }} </h4>' +
                '<div style="clear: both;"></div>' +
                '</div>'
            );

            // Add each table with error checking and proper error handling
            try {
                $.each(tables, function(key, value) {
                    if (value && value.outerHTML) {
                        newWin.document.write(value.outerHTML);
                    }
                });

                // Close the HTML document properly
                newWin.document.write('</body></html>');
                newWin.document.close();

                // Print with timeout to ensure content is loaded
                setTimeout(function() {
                    newWin.print();
                    newWin.close();
                }, 250);
            } catch (e) {
                console.error('Error during print operation:', e);
                newWin.close();
            }
        }

        // Column Visibility Management Functions
        function initializeColumnVisibility() {
            console.log('Initializing column visibility');
            // Load saved column preferences from localStorage
            const savedColumns = JSON.parse(localStorage.getItem('reportColumns_implants') || '{}');

            // Apply saved preferences and set up event listeners
            $('#columns-dropdown input[type="checkbox"]:not([disabled])').each(function() {
                const checkbox = $(this);
                const columnId = checkbox.attr('id');
                const columnIndex = getColumnIndex(columnId);

                console.log('Setting up column:', columnId, 'at index:', columnIndex);

                // Apply saved preference (default to visible if not saved)
                if (savedColumns.hasOwnProperty(columnId)) {
                    checkbox.prop('checked', savedColumns[columnId]);
                } else {
                    checkbox.prop('checked', true);
                }

                // Apply visibility to columns
                toggleColumnVisibility(columnIndex, checkbox.prop('checked'));

                // Add event listener
                checkbox.off('change').on('change', function() {
                    const isVisible = $(this).prop('checked');
                    console.log('Column visibility changed:', columnId, isVisible);
                    toggleColumnVisibility(columnIndex, isVisible);
                    saveColumnPreference(columnId, isVisible);
                });
            });

            // Toggle dropdown visibility
            $('#columns-btn').off('click').on('click', function(e) {
                e.stopPropagation();
                const dropdown = $('#columns-dropdown');
                console.log('Column dropdown clicked, toggling visibility');
                dropdown.toggle();

                // Close dropdown when clicking outside
                $(document).off('click.columnDropdown').on('click.columnDropdown', function(e) {
                    if (!$(e.target).closest('#column-visibility').length) {
                        dropdown.hide();
                        $(document).off('click.columnDropdown');
                    }
                });
            });
        }

        function getColumnIndex(columnId) {
            console.log('Getting column index for:', columnId);
            // Map column IDs to their table indices
            if (columnId === 'col-doctor') return 0;
            if (columnId === 'col-total') return -1; // Last column

            // For abutment columns, extract index from ID
            const match = columnId.match(/col-abutment-(\d+)/);
            if (match) {
                const index = parseInt(match[1]) + 1; // +1 because doctor name is first column
                console.log('Abutment column index:', index);
                return index;
            }

            console.log('Could not determine column index');
            return -1;
        }

        function toggleColumnVisibility(columnIndex, isVisible) {
            console.log('Toggling column visibility for index:', columnIndex, 'visible:', isVisible);
            const displayValue = isVisible ? '' : 'none';

            try {
                if (columnIndex === -1) {
                    // Handle last column (Total)
                    console.log('Toggling last column (Total)');
                    $('.sigma-report-table th:last-child, .sigma-report-table td:last-child').css('display', displayValue);
                } else if (columnIndex >= 0) {
                    // Handle specific column index
                    console.log(`Toggling column at index: ${columnIndex + 1}`);
                    $(`.sigma-report-table th:nth-child(${columnIndex + 1}), .sigma-report-table td:nth-child(${columnIndex + 1})`).css('display', displayValue);
                }
            } catch (e) {
                console.error('Error toggling column visibility:', e);
            }
        }

        function saveColumnPreference(columnId, isVisible) {
            let savedColumns = JSON.parse(localStorage.getItem('reportColumns_implants') || '{}');
            savedColumns[columnId] = isVisible;
            localStorage.setItem('reportColumns_implants', JSON.stringify(savedColumns));
        }

        // Additional utility function for responsive column management
        function handleResponsiveColumns() {
            const screenWidth = $(window).width();
            const $table = $('.sigma-report-table');

            if (screenWidth < 768) {
                // Mobile view - hide less important columns by default
                $table.addClass('mobile-responsive');
            } else if (screenWidth < 1200) {
                // Tablet view - optimized column display
                $table.addClass('tablet-responsive');
            } else {
                // Desktop view - show all columns
                $table.removeClass('mobile-responsive tablet-responsive');
            }
        }

        // Initialize responsive handling
        $(window).on('resize', handleResponsiveColumns);
        handleResponsiveColumns();

        // Monthly Table Collapsible Functionality
        function toggleMonthlyTable(month) {
            const container = document.querySelector(`[data-month="${month}"]`);
            const content = container.querySelector('.monthly-table-content');
            const icon = container.querySelector('.monthly-expand-icon i');

            if (container.classList.contains('expanded')) {
                // Collapse
                container.classList.remove('expanded');
                content.style.maxHeight = '0px';
                icon.className = 'fas fa-chevron-down';

                // Save state to sessionStorage
                sessionStorage.setItem(`monthly_table_${month}`, 'collapsed');
            } else {
                // Expand
                container.classList.add('expanded');
                content.style.maxHeight = content.scrollHeight + 'px';
                icon.className = 'fas fa-chevron-up';

                // Save state to sessionStorage
                sessionStorage.setItem(`monthly_table_${month}`, 'expanded');
            }
        }

        // Initialize monthly table states from sessionStorage
        function initializeMonthlyTableStates() {
            const monthlyContainers = document.querySelectorAll('.monthly-table-container');
            monthlyContainers.forEach(container => {
                const month = container.getAttribute('data-month');
                const savedState = sessionStorage.getItem(`monthly_table_${month}`);
                const content = container.querySelector('.monthly-table-content');
                const icon = container.querySelector('.monthly-expand-icon i');

                if (savedState === 'expanded') {
                    container.classList.add('expanded');
                    content.style.maxHeight = content.scrollHeight + 'px';
                    icon.className = 'fas fa-chevron-up';
                } else {
                    // Default collapsed state
                    container.classList.remove('expanded');
                    content.style.maxHeight = '0px';
                    icon.className = 'fas fa-chevron-down';
                }
            });
        }

        // Make function globally accessible
        window.toggleMonthlyTable = toggleMonthlyTable;

        // Initialize monthly states when monthly breakdown is shown
        $('#monthly-breakdown-toggle').on('click', function() {
            setTimeout(() => {
                initializeMonthlyTableStates();
            }, 350); // Wait for slide animation to complete
        });

        // Initialize the setDisplayMode function so it's available globally
        window.setDisplayMode = window.setDisplayMode || function(mode) {
            console.log('Fallback setDisplayMode called with mode:', mode);
            const toggleOptions = document.querySelectorAll('.toggle-option');
            toggleOptions.forEach(option => {
                if (option.getAttribute('data-mode') === mode) {
                    option.classList.add('active');
                } else {
                    option.classList.remove('active');
                }
            });
        };

        })(jQuery); // End of IIFE with jQuery parameter
    </script>
@endpush
