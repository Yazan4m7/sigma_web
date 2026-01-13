@props([
    'name' => 'datetime',
    'id' => null,
    'value' => '',
    'required' => false,
    'class' => ''
])

@php
    $inputId = $id ?? $name;
    // Parse initial value if provided (format: Y-m-d H:i:s or Y-m-d)
    $initialDate = null;
    if ($value) {
        try {
            $initialDate = \Carbon\Carbon::parse($value);
        } catch (\Exception $e) {
            $initialDate = null;
        }
    }
@endphp

<style>
/* iOS Date Time Picker Scoped Styles */
.ios-dtp-container {
    --ios-primary: #007AFF;
    --ios-primary-hover: #0056b3;
    --ios-primary-light: rgba(0, 122, 255, 0.12);
    --ios-today-bg: rgba(0, 122, 255, 0.15);
    --ios-text-dark: #000000;
    --ios-text-medium: #3c3c43;
    --ios-text-light: rgba(60, 60, 67, 0.6);
    --ios-text-faded: rgba(60, 60, 67, 0.3);
    --ios-bg-white: #ffffff;
    --ios-bg-gray: #f2f2f7;
    --ios-bg-overlay: rgba(0, 0, 0, 0.4);
    --ios-border-color: rgba(60, 60, 67, 0.12);
    --ios-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    --ios-radius: 14px;
    --ios-radius-sm: 10px;
    --ios-item-height: 36px;
    position: relative;
    font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Helvetica Neue', sans-serif;
}

.ios-dtp-trigger {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 8px 12px;
    background: var(--ios-bg-white);
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    text-align: left;
    height: 38px;
}

.ios-dtp-trigger:hover {
    border-color: var(--ios-primary);
}

.ios-dtp-trigger:focus {
    outline: none;
    border-color: var(--ios-primary);
    box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.15);
}

.ios-dtp-backdrop {
    position: fixed;
    inset: 0;
    background: var(--ios-bg-overlay);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    z-index: 1040;
}

.ios-dtp-backdrop.visible {
    opacity: 1;
    visibility: visible;
}

.ios-dtp-modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.95);
    background: var(--ios-bg-white);
    border-radius: var(--ios-radius);
    box-shadow: var(--ios-shadow);
    z-index: 1050;
    overflow: hidden;
    width: min(520px, calc(100vw - 32px));
    max-height: calc(100vh - 32px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.ios-dtp-modal.visible {
    opacity: 1;
    visibility: visible;
    transform: translate(-50%, -50%) scale(1);
}

.ios-dtp-header {
    background: var(--ios-bg-gray);
    padding: 16px 20px;
    text-align: center;
    border-bottom: 1px solid var(--ios-border-color);
}

.ios-dtp-header-text {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15%;
    color: var(--ios-text-dark);
    font-size: 17px;
    font-weight: 600;
    letter-spacing: -0.4px;
}

.ios-dtp-header-date,
.ios-dtp-header-time {
    white-space: nowrap;
}

.ios-dtp-body {
    padding: 12px;
    background: var(--ios-bg-white);
}

.ios-dtp-month-year-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    padding: 0 4px;
}

.ios-dtp-month-year-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    font-size: 15px;
    font-weight: 600;
    color: var(--ios-primary);
    cursor: pointer;
    padding: 8px 12px;
    margin: -8px -12px;
    border-radius: var(--ios-radius-sm);
    transition: background 0.2s ease;
    letter-spacing: -0.4px;
}

.ios-dtp-month-year-btn:hover {
    background: var(--ios-primary-light);
}

.ios-dtp-month-year-btn svg {
    width: 12px;
    height: 12px;
    transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.ios-dtp-month-year-btn.open svg {
    transform: rotate(180deg);
}

.ios-dtp-nav-buttons {
    display: flex;
    gap: 8px;
}

.ios-dtp-nav-buttons.hidden {
    opacity: 0;
    pointer-events: none;
}

.ios-dtp-nav-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: background 0.2s ease;
    color: var(--ios-primary);
}

.ios-dtp-nav-btn:hover {
    background: var(--ios-primary-light);
}

.ios-dtp-nav-btn svg {
    width: 16px;
    height: 16px;
}

.ios-dtp-main-content {
    display: flex;
    gap: 10px;
}

@media (max-width: 480px) {
    .ios-dtp-modal {
        width: calc(100vw - 24px);
    }
    .ios-dtp-main-content {
        gap: 0;
    }
    .ios-dtp-left-panel {
        min-width: 200px;
        margin-right: 10px;
        padding-right: 10px;
        border-right: 1px solid var(--ios-border-color);
    }
    .ios-dtp-right-panel {
        width: 110px !important;
        padding-left: 6px !important;
        border-left: none !important;
    }
    .ios-dtp-wheels-container,
    .ios-dtp-time-wheels-container {
        height: 260px;
        min-height: 260px;
    }
}

.ios-dtp-left-panel {
    flex: 1;
    min-width: 0;
}

.ios-dtp-calendar-section {
    display: flex;
    flex-direction: column;
    animation: iosDtpFadeIn 0.2s ease;
}

.ios-dtp-calendar-section.hidden {
    display: none;
}

@keyframes iosDtpFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.ios-dtp-day-labels {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    margin-bottom: 4px;
}

.ios-dtp-day-label {
    text-align: center;
    font-size: 12px;
    font-weight: 600;
    color: var(--ios-text-light);
    padding: 6px 0;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.ios-dtp-days-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
}

.ios-dtp-day-btn {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 50%;
    font-size: 16px;
    font-weight: 400;
    cursor: pointer;
    transition: all 0.15s ease;
    background: transparent;
    color: var(--ios-text-dark);
    position: relative;
}

.ios-dtp-day-btn:hover:not(.selected) {
    background: var(--ios-bg-gray);
}

.ios-dtp-day-btn.other-month {
    color: var(--ios-text-faded);
}

.ios-dtp-day-btn.today {
    background: var(--ios-today-bg);
    color: var(--ios-primary);
    font-weight: 600;
}

.ios-dtp-day-btn.selected {
    background: var(--ios-primary) !important;
    color: white !important;
    font-weight: 600;
}

.ios-dtp-day-btn.today.selected {
    background: var(--ios-primary) !important;
    color: white !important;
}

.ios-dtp-wheel-section {
    display: none;
    animation: iosDtpFadeIn 0.2s ease;
}

.ios-dtp-wheel-section.visible {
    display: block;
}

.ios-dtp-wheels-container {
    display: flex;
    border-radius: var(--ios-radius-sm);
    overflow: hidden;
    background: var(--ios-bg-gray);
    height: 250px;
    position: relative;
}

.ios-dtp-wheel {
    flex: 1;
    height: 100%;
    overflow: hidden;
    position: relative;
    cursor: grab;
}

.ios-dtp-wheel:active {
    cursor: grabbing;
}

.ios-dtp-wheel + .ios-dtp-wheel {
    border-left: 1px solid var(--ios-border-color);
}

.ios-dtp-wheel-cylinder {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: var(--ios-item-height);
    transform-style: preserve-3d;
    transition: transform 0.12s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.ios-dtp-wheel-cylinder.spinning {
    transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1);
}

.ios-dtp-wheel-item {
    position: absolute;
    width: 100%;
    height: var(--ios-item-height);
    display: flex;
    align-items: center;
    justify-content: center;
    user-select: none;
    font-size: 20px;
    font-weight: 400;
    cursor: pointer;
    letter-spacing: -0.4px;
    backface-visibility: hidden;
    color: var(--ios-text-dark);
}

.ios-dtp-selection-bar {
    position: absolute;
    top: 50%;
    left: 6px;
    right: 6px;
    height: 38px;
    transform: translateY(-50%);
    background: rgba(0, 122, 255, 0.12);
    border-radius: 8px;
    pointer-events: none;
    z-index: 0;
}

.ios-dtp-wheel::before,
.ios-dtp-wheel::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    height: 90px;
    pointer-events: none;
    z-index: 2;
}

.ios-dtp-wheel::before {
    top: 0;
    background: linear-gradient(to bottom, rgba(242, 242, 247, 1) 0%, rgba(242, 242, 247, 0) 100%);
}

.ios-dtp-wheel::after {
    bottom: 0;
    background: linear-gradient(to top, rgba(242, 242, 247, 1) 0%, rgba(242, 242, 247, 0) 100%);
}

.ios-dtp-right-panel {
    width: 150px;
    border-left: 1px solid var(--ios-border-color);
    padding-left: 10px;
    display: flex;
    flex-direction: column;
}

.ios-dtp-time-wheels-container {
    display: flex;
    flex: 1;
    min-height: 220px;
    border-radius: var(--ios-radius-sm);
    overflow: hidden;
    background: var(--ios-bg-gray);
    position: relative;
}

.ios-dtp-time-wheel {
    flex: 1;
    height: 100%;
    overflow: hidden;
    position: relative;
    cursor: grab;
}

.ios-dtp-time-wheel:active {
    cursor: grabbing;
}

.ios-dtp-time-wheel + .ios-dtp-time-wheel {
    border-left: 1px solid var(--ios-border-color);
}

.ios-dtp-time-selection-bar {
    position: absolute;
    top: 50%;
    left: 6px;
    right: 6px;
    height: 38px;
    transform: translateY(-50%);
    background: rgba(0, 122, 255, 0.12);
    border-radius: 8px;
    pointer-events: none;
    z-index: 0;
}

.ios-dtp-time-wheels-container::before,
.ios-dtp-time-wheels-container::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    height: 80px;
    pointer-events: none;
    z-index: 2;
}

.ios-dtp-time-wheels-container::before {
    top: 0;
    background: linear-gradient(to bottom, rgba(242, 242, 247, 1) 0%, rgba(242, 242, 247, 0) 100%);
}

.ios-dtp-time-wheels-container::after {
    bottom: 0;
    background: linear-gradient(to top, rgba(242, 242, 247, 1) 0%, rgba(242, 242, 247, 0) 100%);
}

.ios-dtp-time-cylinder {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: var(--ios-item-height);
    transform-style: preserve-3d;
    transition: transform 0.12s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.ios-dtp-time-cylinder.spinning {
    transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1);
}

.ios-dtp-time-item {
    position: absolute;
    width: 100%;
    height: var(--ios-item-height);
    display: flex;
    align-items: center;
    justify-content: center;
    user-select: none;
    font-size: 18px;
    font-weight: 400;
    cursor: pointer;
    letter-spacing: -0.2px;
    backface-visibility: hidden;
    color: var(--ios-text-dark);
}

.ios-dtp-footer {
    padding: 10px 12px 14px;
    background: var(--ios-bg-white);
    display: flex;
    justify-content: center;
}

.ios-dtp-set-btn {
    padding: 10px 36px;
    border: none;
    border-radius: var(--ios-radius-sm);
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    letter-spacing: -0.2px;
    background: var(--ios-primary);
    color: white;
}

.ios-dtp-set-btn:hover {
    background: var(--ios-primary-hover);
}

.ios-dtp-set-btn:active {
    transform: scale(0.98);
}
</style>

<div class="ios-dtp-container {{ $class }}" x-data="iosDtp_{{ $inputId }}()" x-init="init()">
    <!-- Hidden input for form submission -->
    <input type="hidden" name="{{ $name }}" id="{{ $inputId }}" x-model="formValue" {{ $required ? 'required' : '' }}>

    <!-- Trigger Button styled as form input -->
    <button type="button" class="ios-dtp-trigger" @click="openPicker()" x-text="formatDisplayDate()"></button>

    <!-- Backdrop -->
    <div class="ios-dtp-backdrop" :class="{ 'visible': open }" @click="closePicker()"></div>

    <!-- Picker Modal -->
    <div class="ios-dtp-modal" :class="{ 'visible': open }">
        <!-- Header -->
        <div class="ios-dtp-header">
            <div class="ios-dtp-header-text">
                <span class="ios-dtp-header-date" x-text="formatHeaderDatePart()"></span>
                <span class="ios-dtp-header-time" x-text="getFormattedTime()"></span>
            </div>
        </div>

        <!-- Body -->
        <div class="ios-dtp-body">
            <!-- Month/Year Toggle Header -->
            <div class="ios-dtp-month-year-header">
                <button
                    type="button"
                    class="ios-dtp-month-year-btn"
                    :class="{ 'open': view === 'wheel' }"
                    @click="toggleView()"
                >
                    <span x-text="months[selectedMonth] + ' ' + selectedYear"></span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="ios-dtp-nav-buttons" :class="{ 'hidden': view === 'wheel' }">
                    <button type="button" class="ios-dtp-nav-btn" @click="prevMonth()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button type="button" class="ios-dtp-nav-btn" @click="nextMonth()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="ios-dtp-main-content">
                <!-- Left Panel -->
                <div class="ios-dtp-left-panel">
                    <!-- Calendar View -->
                    <div class="ios-dtp-calendar-section" :class="{ 'hidden': view === 'wheel' }">
                        <div class="ios-dtp-day-labels">
                            <template x-for="day in dayLabels" :key="day">
                                <div class="ios-dtp-day-label" x-text="day"></div>
                            </template>
                        </div>
                        <div class="ios-dtp-days-grid">
                            <template x-for="(day, index) in calendarDays" :key="'day-' + index">
                                <button
                                    type="button"
                                    class="ios-dtp-day-btn"
                                    :class="{
                                        'other-month': !day.currentMonth,
                                        'today': day.isToday,
                                        'selected': day.date === selectedDay && day.month === selectedMonth && day.year === selectedYear
                                    }"
                                    @click="day.currentMonth && selectCalendarDay(day)"
                                    :disabled="!day.currentMonth"
                                    x-text="day.date"
                                ></button>
                            </template>
                        </div>
                    </div>

                    <!-- Wheel View -->
                    <div class="ios-dtp-wheel-section" :class="{ 'visible': view === 'wheel' }">
                        <div class="ios-dtp-wheels-container">
                            <div class="ios-dtp-selection-bar"></div>
                            <!-- Year Wheel -->
                            <div class="ios-dtp-wheel"
                                 @wheel.prevent="scrollWheel('year', $event)"
                                 @mousedown.prevent="startDrag('year', $event)"
                                 @touchstart.prevent="startDrag('year', $event)">
                                <div class="ios-dtp-wheel-cylinder" :class="{ 'spinning': spinning.year }" :style="'transform: translateY(-50%) rotateX(' + yearRotation + 'deg)'">
                                    <template x-for="(year, index) in yearOptions" :key="'year-' + year">
                                        <div class="ios-dtp-wheel-item"
                                             :style="getItemStyle(index, yearOptions.length, 'year')"
                                             @click="!dragState.hasMoved && selectYearDirect(year)"
                                             x-text="year"></div>
                                    </template>
                                </div>
                            </div>
                            <!-- Month Wheel -->
                            <div class="ios-dtp-wheel"
                                 @wheel.prevent="scrollWheel('month', $event)"
                                 @mousedown.prevent="startDrag('month', $event)"
                                 @touchstart.prevent="startDrag('month', $event)">
                                <div class="ios-dtp-wheel-cylinder" :class="{ 'spinning': spinning.month }" :style="'transform: translateY(-50%) rotateX(' + monthRotation + 'deg)'">
                                    <template x-for="(item, index) in virtualMonths" :key="'month-' + index">
                                        <div class="ios-dtp-wheel-item"
                                             :style="getItemStyle(index, virtualMonths.length, 'month')"
                                             @click="!dragState.hasMoved && selectMonthDirect(item.realIndex)"
                                             x-text="item.name"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel - Time Wheels -->
                <div class="ios-dtp-right-panel">
                    <div class="ios-dtp-time-wheels-container">
                        <div class="ios-dtp-time-selection-bar"></div>
                        <!-- Hour -->
                        <div class="ios-dtp-time-wheel"
                             @wheel.prevent="scrollWheel('hour', $event)"
                             @mousedown.prevent="startDrag('hour', $event)"
                             @touchstart.prevent="startDrag('hour', $event)">
                            <div class="ios-dtp-time-cylinder" :class="{ 'spinning': spinning.hour }" :style="'transform: translateY(-50%) rotateX(' + hourRotation + 'deg)'">
                                <template x-for="(item, index) in virtualHours" :key="'hour-' + index">
                                    <div class="ios-dtp-time-item"
                                         :style="getItemStyle(index, virtualHours.length, 'hour')"
                                         @click="!dragState.hasMoved && selectHourDirect(item.realIndex)"
                                         x-text="item.name"></div>
                                </template>
                            </div>
                        </div>
                        <!-- Minute -->
                        <div class="ios-dtp-time-wheel"
                             @wheel.prevent="scrollWheel('minute', $event)"
                             @mousedown.prevent="startDrag('minute', $event)"
                             @touchstart.prevent="startDrag('minute', $event)">
                            <div class="ios-dtp-time-cylinder" :class="{ 'spinning': spinning.minute }" :style="'transform: translateY(-50%) rotateX(' + minuteRotation + 'deg)'">
                                <template x-for="(minute, index) in minuteOptions" :key="'minute-' + index">
                                    <div class="ios-dtp-time-item"
                                         :style="getItemStyle(index, minuteOptions.length, 'minute')"
                                         @click="!dragState.hasMoved && selectMinuteDirect(index)"
                                         x-text="minute"></div>
                                </template>
                            </div>
                        </div>
                        <!-- AM/PM -->
                        <div class="ios-dtp-time-wheel"
                             @wheel.prevent="scrollWheel('ampm', $event)"
                             @mousedown.prevent="startDrag('ampm', $event)"
                             @touchstart.prevent="startDrag('ampm', $event)">
                            <div class="ios-dtp-time-cylinder" :class="{ 'spinning': spinning.ampm }" :style="'transform: translateY(-50%) rotateX(' + ampmRotation + 'deg)'">
                                <template x-for="(ampm, index) in ampmOptions" :key="'ampm-' + index">
                                    <div class="ios-dtp-time-item"
                                         :style="getItemStyle(index, ampmOptions.length, 'ampm')"
                                         @click="!dragState.hasMoved && selectAmpmDirect(index)"
                                         x-text="ampm"></div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="ios-dtp-footer">
            <button type="button" class="ios-dtp-set-btn" @click="confirmSelection()">SET</button>
        </div>
    </div>
</div>

<script>
function iosDtp_{{ $inputId }}() {
    const now = new Date();
    const todayYear = now.getFullYear();
    const todayMonth = now.getMonth();
    const todayDate = now.getDate();

    // Parse initial value
    @if($initialDate)
    const initYear = {{ $initialDate->year }};
    const initMonth = {{ $initialDate->month - 1 }};
    const initDay = {{ $initialDate->day }};
    const initHour = {{ $initialDate->hour }};
    const initMinute = {{ $initialDate->minute }};
    @else
    const initYear = todayYear;
    const initMonth = todayMonth;
    const initDay = todayDate;
    const initHour = 7;
    const initMinute = 0;
    @endif

    // Convert 24h to 12h format
    let hour12 = initHour % 12;
    if (hour12 === 0) hour12 = 12;
    const hourIndex = hour12 - 1;
    const ampmIndex = initHour >= 12 ? 1 : 0;
    const minuteIndex = initMinute >= 30 ? 1 : 0;

    const itemHeight = 36;
    const anglePerItem = 15;
    const radius = (itemHeight / 2) / Math.tan((anglePerItem / 2) * Math.PI / 180);
    const dragSensitivity = 0.4;

    const hours = [];
    for (let h = 1; h <= 12; h++) {
        hours.push(h.toString());
    }

    const minutes = ['00', '30'];
    const ampm = ['AM', 'PM'];

    const years = [];
    for (let y = todayYear - 10; y <= todayYear + 10; y++) {
        years.push(y);
    }

    const virtualCopies = 5;

    const baseMonths = ['January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'];
    const virtualMonths = [];
    for (let copy = -virtualCopies; copy <= virtualCopies; copy++) {
        baseMonths.forEach((m, i) => {
            virtualMonths.push({ name: m, realIndex: i, copy });
        });
    }

    const baseHours = hours.slice();
    const virtualHours = [];
    for (let copy = -virtualCopies; copy <= virtualCopies; copy++) {
        baseHours.forEach((h, i) => {
            virtualHours.push({ name: h, realIndex: i, copy });
        });
    }

    return {
        open: false,
        view: 'calendar',
        formValue: '{{ $value }}',

        todayYear,
        todayMonth,
        todayDate,

        selectedYear: initYear,
        selectedMonth: initMonth,
        selectedDay: initDay,
        selectedHourIndex: hourIndex,
        selectedMinuteIndex: minuteIndex,
        selectedAmpmIndex: ampmIndex,

        months: baseMonths,
        virtualMonths: virtualMonths,
        dayLabels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        hourOptions: baseHours,
        virtualHours: virtualHours,
        minuteOptions: minutes,
        ampmOptions: ampm,
        yearOptions: years,

        monthVirtualOffset: virtualCopies * 12,
        hourVirtualOffset: virtualCopies * 12,

        yearRotation: 0,
        monthRotation: 0,
        hourRotation: 0,
        minuteRotation: 0,
        ampmRotation: 0,

        spinning: {
            year: false,
            month: false,
            hour: false,
            minute: false,
            ampm: false
        },

        dragState: {
            active: false,
            type: null,
            startY: 0,
            startRotation: 0,
            lastY: 0,
            lastTime: 0,
            velocity: 0,
            hasMoved: false
        },

        boundMouseMove: null,
        boundMouseUp: null,
        boundTouchMove: null,
        boundTouchEnd: null,

        init() {
            this.updateRotations();
            this.updateFormValue();

            this.boundMouseMove = (e) => this.onGlobalDrag(e);
            this.boundMouseUp = (e) => this.onGlobalDragEnd(e);
            this.boundTouchMove = (e) => this.onGlobalDrag(e);
            this.boundTouchEnd = (e) => this.onGlobalDragEnd(e);
        },

        getItemStyle(index, total, type) {
            const angle = index * anglePerItem;
            const currentRotation = this[this.getRotationProp(type)];
            const selectedIndex = Math.round(currentRotation / anglePerItem);
            const distance = Math.abs(index - selectedIndex);

            let opacity = 1;
            if (distance === 1) opacity = 0.35;
            else if (distance === 2) opacity = 0.28;
            else if (distance === 3) opacity = 0.2;
            else if (distance >= 4) opacity = 0.12;

            return `transform: rotateX(${-angle}deg) translateZ(${radius}px); opacity: ${opacity};`;
        },

        updateRotations() {
            const yearIndex = this.yearOptions.indexOf(this.selectedYear);
            this.yearRotation = yearIndex * anglePerItem;
            this.monthRotation = (this.monthVirtualOffset + this.selectedMonth) * anglePerItem;
            this.hourRotation = (this.hourVirtualOffset + this.selectedHourIndex) * anglePerItem;
            this.minuteRotation = this.selectedMinuteIndex * anglePerItem;
            this.ampmRotation = this.selectedAmpmIndex * anglePerItem;
        },

        updateFormValue() {
            // Convert to 24h format for backend
            let hour24 = this.selectedHourIndex + 1;
            if (this.selectedAmpmIndex === 1 && hour24 !== 12) {
                hour24 += 12;
            } else if (this.selectedAmpmIndex === 0 && hour24 === 12) {
                hour24 = 0;
            }

            const month = String(this.selectedMonth + 1).padStart(2, '0');
            const day = String(this.selectedDay).padStart(2, '0');
            const hourStr = String(hour24).padStart(2, '0');
            const minuteStr = this.minuteOptions[this.selectedMinuteIndex];

            this.formValue = `${this.selectedYear}-${month}-${day} ${hourStr}:${minuteStr}:00`;
        },

        openPicker() {
            this.open = true;
            document.body.style.overflow = 'hidden';
        },

        closePicker() {
            this.open = false;
            document.body.style.overflow = '';
        },

        toggleView() {
            this.view = this.view === 'calendar' ? 'wheel' : 'calendar';
        },

        get calendarDays() {
            const days = [];
            const firstDay = new Date(this.selectedYear, this.selectedMonth, 1);
            const lastDay = new Date(this.selectedYear, this.selectedMonth + 1, 0);
            const startDay = firstDay.getDay();

            const prevMonth = this.selectedMonth === 0 ? 11 : this.selectedMonth - 1;
            const prevYear = this.selectedMonth === 0 ? this.selectedYear - 1 : this.selectedYear;
            const prevMonthLastDay = new Date(this.selectedYear, this.selectedMonth, 0).getDate();

            for (let i = startDay - 1; i >= 0; i--) {
                const date = prevMonthLastDay - i;
                const isToday = (prevYear === this.todayYear && prevMonth === this.todayMonth && date === this.todayDate);
                days.push({
                    date,
                    currentMonth: false,
                    isToday,
                    month: prevMonth,
                    year: prevYear
                });
            }

            for (let i = 1; i <= lastDay.getDate(); i++) {
                const isToday = (this.selectedYear === this.todayYear &&
                                 this.selectedMonth === this.todayMonth &&
                                 i === this.todayDate);
                days.push({
                    date: i,
                    currentMonth: true,
                    isToday,
                    month: this.selectedMonth,
                    year: this.selectedYear
                });
            }

            const nextMonth = this.selectedMonth === 11 ? 0 : this.selectedMonth + 1;
            const nextYear = this.selectedMonth === 11 ? this.selectedYear + 1 : this.selectedYear;
            const remaining = 42 - days.length;

            for (let i = 1; i <= remaining; i++) {
                const isToday = (nextYear === this.todayYear && nextMonth === this.todayMonth && i === this.todayDate);
                days.push({
                    date: i,
                    currentMonth: false,
                    isToday,
                    month: nextMonth,
                    year: nextYear
                });
            }

            return days;
        },

        selectCalendarDay(day) {
            this.selectedDay = day.date;
            this.selectedMonth = day.month;
            this.selectedYear = day.year;
            this.updateRotations();
        },

        prevMonth() {
            if (this.selectedMonth === 0) {
                this.selectedMonth = 11;
                this.selectedYear--;
            } else {
                this.selectedMonth--;
            }
            this.adjustSelectedDay();
            this.updateRotations();
        },

        nextMonth() {
            if (this.selectedMonth === 11) {
                this.selectedMonth = 0;
                this.selectedYear++;
            } else {
                this.selectedMonth++;
            }
            this.adjustSelectedDay();
            this.updateRotations();
        },

        adjustSelectedDay() {
            const lastDay = new Date(this.selectedYear, this.selectedMonth + 1, 0).getDate();
            if (this.selectedDay > lastDay) {
                this.selectedDay = lastDay;
            }
        },

        selectYearDirect(year) {
            this.selectedYear = year;
            this.updateRotations();
            this.adjustSelectedDay();
        },

        selectMonthDirect(realIndex) {
            this.selectedMonth = realIndex;
            this.monthVirtualOffset = virtualCopies * 12;
            this.updateRotations();
            this.adjustSelectedDay();
        },

        selectHourDirect(realIndex) {
            this.selectedHourIndex = realIndex;
            this.hourVirtualOffset = virtualCopies * 12;
            this.updateRotations();
        },

        selectMinuteDirect(index) {
            this.selectedMinuteIndex = index;
            this.updateRotations();
        },

        selectAmpmDirect(index) {
            this.selectedAmpmIndex = index;
            this.updateRotations();
        },

        getRotationProp(type) {
            const map = { year: 'yearRotation', month: 'monthRotation', hour: 'hourRotation', minute: 'minuteRotation', ampm: 'ampmRotation' };
            return map[type];
        },

        scrollWheel(type, e) {
            const direction = e.deltaY > 0 ? 1 : -1;
            const prop = this.getRotationProp(type);

            const currentIndex = Math.round(this[prop] / anglePerItem);
            let newIndex = currentIndex + direction;

            if (type === 'year') {
                newIndex = Math.max(0, Math.min(this.yearOptions.length - 1, newIndex));
            } else if (type === 'minute') {
                newIndex = Math.max(0, Math.min(this.minuteOptions.length - 1, newIndex));
            } else if (type === 'ampm') {
                newIndex = Math.max(0, Math.min(this.ampmOptions.length - 1, newIndex));
            }

            this[prop] = newIndex * anglePerItem;
            this.updateSelectionFromRotation(type);
        },

        startDrag(type, e) {
            e.preventDefault();
            this.spinning[type] = false;
            this.dragState.active = true;
            this.dragState.type = type;
            this.dragState.startY = e.clientY || (e.touches && e.touches[0].clientY);
            this.dragState.startRotation = this[this.getRotationProp(type)];
            this.dragState.lastY = this.dragState.startY;
            this.dragState.lastTime = Date.now();
            this.dragState.velocity = 0;
            this.dragState.hasMoved = false;

            document.addEventListener('mousemove', this.boundMouseMove);
            document.addEventListener('mouseup', this.boundMouseUp);
            document.addEventListener('touchmove', this.boundTouchMove, { passive: false });
            document.addEventListener('touchend', this.boundTouchEnd);
        },

        onGlobalDrag(e) {
            if (!this.dragState.active) return;
            e.preventDefault();

            const clientY = e.clientY || (e.touches && e.touches[0].clientY);
            const delta = this.dragState.startY - clientY;

            if (Math.abs(delta) > 3) {
                this.dragState.hasMoved = true;
            }

            const prop = this.getRotationProp(this.dragState.type);

            const now = Date.now();
            const dt = now - this.dragState.lastTime;
            if (dt > 0) {
                this.dragState.velocity = (this.dragState.lastY - clientY) / dt;
            }
            this.dragState.lastY = clientY;
            this.dragState.lastTime = now;

            this[prop] = this.dragState.startRotation + (delta * dragSensitivity);
        },

        onGlobalDragEnd(e) {
            if (!this.dragState.active) return;

            document.removeEventListener('mousemove', this.boundMouseMove);
            document.removeEventListener('mouseup', this.boundMouseUp);
            document.removeEventListener('touchmove', this.boundTouchMove);
            document.removeEventListener('touchend', this.boundTouchEnd);

            const type = this.dragState.type;
            this.dragState.active = false;

            const prop = this.getRotationProp(type);
            const velocity = this.dragState.velocity;

            if (Math.abs(velocity) > 0.4) {
                this.spinning[type] = true;

                const momentum = velocity * 100;
                let targetRotation = this[prop] + momentum;

                let targetIndex = Math.round(targetRotation / anglePerItem);

                if (type === 'year') {
                    targetIndex = Math.max(0, Math.min(this.yearOptions.length - 1, targetIndex));
                } else if (type === 'minute') {
                    targetIndex = Math.max(0, Math.min(this.minuteOptions.length - 1, targetIndex));
                } else if (type === 'ampm') {
                    targetIndex = Math.max(0, Math.min(this.ampmOptions.length - 1, targetIndex));
                }

                this[prop] = targetIndex * anglePerItem;

                setTimeout(() => {
                    this.spinning[type] = false;
                    this.updateSelectionFromRotation(type);
                }, 500);
            } else {
                let index = Math.round(this[prop] / anglePerItem);

                if (type === 'year') {
                    index = Math.max(0, Math.min(this.yearOptions.length - 1, index));
                } else if (type === 'minute') {
                    index = Math.max(0, Math.min(this.minuteOptions.length - 1, index));
                } else if (type === 'ampm') {
                    index = Math.max(0, Math.min(this.ampmOptions.length - 1, index));
                }

                this[prop] = index * anglePerItem;
                this.updateSelectionFromRotation(type);
            }
        },

        updateSelectionFromRotation(type) {
            const prop = this.getRotationProp(type);
            const index = Math.round(this[prop] / anglePerItem);

            if (type === 'year') {
                const clampedIndex = Math.max(0, Math.min(this.yearOptions.length - 1, index));
                this.selectedYear = this.yearOptions[clampedIndex];
                this.adjustSelectedDay();
            } else if (type === 'month') {
                const realMonth = ((index % 12) + 12) % 12;
                this.selectedMonth = realMonth;
                this.adjustSelectedDay();
            } else if (type === 'hour') {
                const realHour = ((index % 12) + 12) % 12;
                this.selectedHourIndex = realHour;
            } else if (type === 'minute') {
                this.selectedMinuteIndex = index;
            } else if (type === 'ampm') {
                this.selectedAmpmIndex = index;
            }
        },

        getFormattedTime() {
            return `${this.hourOptions[this.selectedHourIndex]}:${this.minuteOptions[this.selectedMinuteIndex]} ${this.ampmOptions[this.selectedAmpmIndex]}`;
        },

        formatDisplayDate() {
            if (!this.formValue && !this.selectedDay) {
                return 'Select date & time';
            }
            const day = this.selectedDay;
            const month = this.months[this.selectedMonth].substring(0, 3);
            return `${month} ${day}, ${this.selectedYear} · ${this.getFormattedTime()}`;
        },

        formatHeaderDatePart() {
            const day = this.selectedDay;
            const month = this.months[this.selectedMonth];
            return `${month} ${day}, ${this.selectedYear}`;
        },

        confirmSelection() {
            this.updateFormValue();
            this.closePicker();
        }
    };
}
</script>
