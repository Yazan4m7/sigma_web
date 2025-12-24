{{-- resources/views/components/date-time-picker.blade.php --}}
@props([
    'name' => 'datetime',
    'value' => '',
    'label' => 'Select Date & Time',
    'id' => null,
])

@php
    $uniqueId = $id ?? 'dtp_' . uniqid();
@endphp

@once
    <script>
        window.dateTimePickerData = function(uniqueId, name, initialValue) {
            return {
                name: name,
                open: false,
                displayValue: '',
                hiddenValue: '',
                preview: '',
                state: {
                    year: new Date().getFullYear(),
                    month: new Date().getMonth(),
                    day: new Date().getDate(),
                    hour: 12,
                    minute: 0,
                    ampm: 'AM'
                },
                wheels: {},
                ITEM_HEIGHT: 32,
                MONTHS: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                DAYS: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],

                init() {
                    this.initState();
                    this.$nextTick(() => {
                        this.setupWheels();
                        this.updatePreview();
                        this.displayValue = this.preview;
                        this.hiddenValue = this.preview;
                    });
                },

                initState() {
                    const now = new Date();
                    this.state.year = now.getFullYear();
                    this.state.month = now.getMonth();
                    this.state.day = now.getDate();
                    const h24 = now.getHours();
                    this.state.ampm = h24 >= 12 ? "PM" : "AM";
                    this.state.hour = h24 % 12 || 12;
                    this.state.minute = Math.round(now.getMinutes() / 5) * 5;
                    if (this.state.minute === 60) this.state.minute = 55;
                },

                setupWheels() {
                    const container = this.$el.querySelector('.picker-container-' + uniqueId);
                    if (!container) return;

                    const wheels = container.querySelectorAll('.picker-wheel');
                    const currentYear = new Date().getFullYear();

                    wheels.forEach(wheel => {
                        const type = wheel.dataset.type;
                        const list = wheel.querySelector('ul');

                        if (type === 'year') {
                            for (let y = currentYear - 10; y <= currentYear + 10; y++) {
                                list.appendChild(this.createItem(y, y));
                            }
                        } else if (type === 'month') {
                            this.MONTHS.forEach((m, i) => list.appendChild(this.createItem(m, i)));
                        } else if (type === 'hour') {
                            for (let h = 1; h <= 12; h++) list.appendChild(this.createItem(h, h));
                        } else if (type === 'minute') {
                            for (let m = 0; m < 60; m += 5) list.appendChild(this.createItem(m, m));
                        } else if (type === 'day') {
                            this.updateDayWheel(wheel);
                        }

                        this.wheels[type] = wheel;
                        this.attachWheelEvents(wheel, type);
                    });

                    setTimeout(() => {
                        this.setWheelValue('year', this.state.year, true);
                        this.setWheelValue('month', this.state.month, true);
                        this.setWheelValue('day', this.state.day, true);
                        this.setWheelValue('hour', this.state.hour, true);
                        this.setWheelValue('minute', this.state.minute, true);
                        this.setWheelValue('ampm', this.state.ampm, true);
                    }, 50);
                },

                createItem(label, value) {
                    const li = document.createElement('li');
                    li.className = 'picker-item';
                    li.textContent = typeof label === 'number' ? String(label).padStart(2, '0') : label;
                    li.dataset.value = value;
                    return li;
                },

                updateDayWheel(wheel) {
                    const list = wheel.querySelector('ul');
                    const days = new Date(this.state.year, this.state.month + 1, 0).getDate();
                    list.innerHTML = '';
                    for (let d = 1; d <= days; d++) list.appendChild(this.createItem(d, d));
                    if (this.state.day > days) this.state.day = days;
                },

                attachWheelEvents(wheel, type) {
                    let isDragging = false;
                    let startY = 0;
                    let startScrollTop = 0;
                    let scrollTimeout = null;

                    const mousedown = (e) => {
                        isDragging = true;
                        startY = e.pageY;
                        startScrollTop = wheel.scrollTop;
                        wheel.style.scrollSnapType = 'none';
                    };

                    const mousemove = (e) => {
                        if (!isDragging) return;
                        wheel.scrollTop = startScrollTop + (startY - e.pageY);
                    };

                    const mouseup = () => {
                        if (isDragging) {
                            isDragging = false;
                            wheel.style.scrollSnapType = 'y mandatory';
                            this.handleScrollEnd(type, wheel);
                        }
                    };

                    wheel.addEventListener('mousedown', mousedown);
                    document.addEventListener('mousemove', mousemove);
                    document.addEventListener('mouseup', mouseup);

                    wheel.addEventListener('scroll', () => {
                        clearTimeout(scrollTimeout);
                        const index = Math.round(wheel.scrollTop / this.ITEM_HEIGHT);
                        const items = wheel.querySelectorAll('.picker-item');
                        items.forEach((item, i) => {
                            const diff = Math.abs(i - index);
                            item.classList.toggle('selected', i === index);
                            item.classList.toggle('near', diff > 0 && diff <= 3);
                        });

                        scrollTimeout = setTimeout(() => {
                            if (!isDragging) this.handleScrollEnd(type, wheel);
                        }, 100);
                    });
                },

                handleScrollEnd(type, wheel) {
                    const index = Math.round(wheel.scrollTop / this.ITEM_HEIGHT);
                    const items = wheel.querySelectorAll('.picker-item');
                    const item = items[index];
                    if (!item) return;

                    const val = item.dataset.value;
                    const oldMonth = this.state.month;
                    const oldYear = this.state.year;

                    this.state[type] = (type === 'ampm') ? val : parseInt(val);

                    if ((type === 'month' || type === 'year') && (oldMonth !== this.state.month || oldYear !== this.state.year)) {
                        this.updateDayWheel(this.wheels.day);
                        const max = new Date(this.state.year, this.state.month + 1, 0).getDate();
                        if (this.state.day > max) this.state.day = max;
                        this.setWheelValue('day', this.state.day);
                    }
                    this.updatePreview();
                },

                setWheelValue(type, value, immediate = false) {
                    const wheel = this.wheels[type];
                    if (!wheel) return;
                    const items = Array.from(wheel.querySelectorAll('.picker-item'));
                    const index = items.findIndex(it => it.dataset.value == value);
                    if (index !== -1) {
                        wheel.scrollTo({ top: index * this.ITEM_HEIGHT, behavior: immediate ? 'auto' : 'smooth' });
                    }
                },

                updatePreview() {
                    const h = String(this.state.hour).padStart(2, '0');
                    const m = String(this.state.minute).padStart(2, '0');
                    const dateObj = new Date(this.state.year, this.state.month, this.state.day);
                    const dayName = this.DAYS[dateObj.getDay()];
                    const monthName = this.MONTHS[this.state.month];
                    this.preview = `${dayName}, ${this.state.year}-${monthName}-${String(this.state.day).padStart(2, '0')} ${h}:${m} ${this.state.ampm}`;
                },

                cancel() {
                    this.open = false;
                    document.body.style.overflow = '';
                },

                confirm() {
                    this.displayValue = this.preview;
                    this.hiddenValue = this.preview;
                    this.open = false;
                    document.body.style.overflow = '';
                },

                openModal() {
                    this.open = true;
                    document.body.style.overflow = 'hidden';
                }
            }
        }
    </script>

    <style>
        .dtp-wrapper {
            position: relative;
        }

        .dtp-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #e2e8f0;
        }

        .dtp-button {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #475569;
            border-radius: 10px;
            background-color: #1e293b;
            color: white;
            text-align: center;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: border-color 0.2s;
        }

        .dtp-button:hover {
            border-color: #3b82f6;
        }

        .dtp-modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(2px);
            z-index: 9999;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .dtp-modal-content {
            background-color: #111827;
            width: 100%;
            max-width: 480px;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        .dtp-header {
            border-bottom: 1px solid #374151;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dtp-cancel-btn,
        .dtp-done-btn {
            background: none;
            border: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            padding: 4px 8px;
        }

        .dtp-cancel-btn {
            color: #94a3b8;
        }

        .dtp-done-btn {
            color: #3b82f6;
            font-weight: 700;
        }

        .dtp-header-title {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .dtp-picker-section {
            padding: 16px;
        }

        .dtp-section-labels {
            display: flex;
            width: 100%;
            margin-bottom: 8px;
        }

        .dtp-section-label {
            flex: 1;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .dtp-section-spacer {
            width: 32px;
        }

        .dtp-picker-container {
            display: flex;
            background-color: #1e293b;
            border-radius: 12px;
            padding: 4px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            height: 200px;
            position: relative;
            overflow: hidden;
            border: 1px solid #334155;
        }

        .picker-highlight {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 32px;
            margin-top: -16px;
            pointer-events: none;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(255,255,255,0.03);
            z-index: 10;
        }

        .picker-wheel {
            height: 100%;
            overflow-y: scroll;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-snap-type: y mandatory;
            -webkit-mask-image: linear-gradient(transparent, black 10%, black 90%, transparent);
            mask-image: linear-gradient(transparent, black 10%, black 90%, transparent);
            cursor: grab;
        }

        .picker-wheel::-webkit-scrollbar {
            display: none;
        }

        .picker-wheel:active {
            cursor: grabbing;
        }

        .picker-wheel[data-type="year"] { flex: 1.3; }
        .picker-wheel[data-type="month"] { flex: 1.1; }
        .picker-wheel[data-type="day"] { flex: 0.8; }
        .picker-wheel[data-type="hour"] { flex: 0.8; }
        .picker-wheel[data-type="minute"] { flex: 0.8; }
        .picker-wheel[data-type="ampm"] { flex: 1.1; }

        .picker-wheel ul {
            list-style: none;
            margin: 0;
            padding: 84px 0;
            text-align: center;
        }

        .picker-item {
            height: 32px;
            line-height: 32px;
            color: #94a3b8;
            font-size: 12px;
            opacity: 0.15;
            transition: opacity 0.2s, color 0.2s, font-size 0.2s;
            scroll-snap-align: center;
            user-select: none;
            white-space: nowrap;
        }

        .picker-item.selected {
            opacity: 1;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }

        .picker-item.near {
            opacity: 0.5;
        }

        .dtp-divider {
            width: 1px;
            height: 50%;
            background: rgba(255, 255, 255, 0.1);
            align-self: center;
            margin: 0 12px;
            flex-shrink: 0;
        }

        .dtp-time-separator {
            display: flex;
            align-items: center;
            font-weight: bold;
            color: white;
            font-size: 14px;
            margin: 0 -2px;
            flex-shrink: 0;
        }

        .dtp-preview-section {
            padding: 16px 16px 24px;
            text-align: center;
        }

        .dtp-preview-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .dtp-preview-value {
            font-size: 13px;
            color: #94a3b8;
            font-weight: 500;
        }

        [x-cloak] {
            display: none !important;
        }

        @media (min-width: 640px) {
            .dtp-modal-overlay {
                align-items: center;
            }

            .dtp-modal-content {
                border-radius: 16px;
            }
        }
    </style>
@endonce

<div x-data="dateTimePickerData('{{ $uniqueId }}', '{{ $name }}', '{{ $value }}')" class="dtp-wrapper">
    @if($label)
        <label class="dtp-label">{{ $label }}</label>
    @endif

    <button
            type="button"
            @click="openModal()"
            class="dtp-button"
    >
        <span x-text="displayValue || '{{ $label }}'"></span>
    </button>

    <input type="hidden" :name="name" :value="hiddenValue" />

    {{-- Modal --}}
    <div
            x-show="open"
            x-cloak
            @click.self="cancel()"
            class="dtp-modal-overlay"
            style="display: none;"
    >
        <div
                @click.stop
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-full"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="dtp-modal-content"
        >
            {{-- Header --}}
            <div class="dtp-header">
                <button @click="cancel()" type="button" class="dtp-cancel-btn">Cancel</button>
                <h3 class="dtp-header-title">Date & Time</h3>
                <button @click="confirm()" type="button" class="dtp-done-btn">Done</button>
            </div>

            {{-- Picker Container --}}
            <div class="dtp-picker-section">
                <div class="dtp-section-labels">
                    <div class="dtp-section-label">Date</div>
                    <div class="dtp-section-spacer"></div>
                    <div class="dtp-section-label">Time</div>
                </div>

                <div class="dtp-picker-container picker-container-{{ $uniqueId }}">
                    <div class="picker-highlight"></div>

                    {{-- Year --}}
                    <div class="picker-wheel" data-type="year">
                        <ul></ul>
                    </div>

                    {{-- Month --}}
                    <div class="picker-wheel" data-type="month">
                        <ul></ul>
                    </div>

                    {{-- Day --}}
                    <div class="picker-wheel" data-type="day">
                        <ul></ul>
                    </div>

                    <div class="dtp-divider"></div>

                    {{-- Hour --}}
                    <div class="picker-wheel" data-type="hour">
                        <ul></ul>
                    </div>

                    <div class="dtp-time-separator">:</div>

                    {{-- Minute --}}
                    <div class="picker-wheel" data-type="minute">
                        <ul></ul>
                    </div>

                    {{-- AM/PM --}}
                    <div class="picker-wheel" data-type="ampm">
                        <ul>
                            <li class="picker-item" data-value="AM">AM</li>
                            <li class="picker-item" data-value="PM">PM</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Preview --}}
            <div class="dtp-preview-section">
                <p class="dtp-preview-label">Selected Date</p>
                <span x-text="preview" class="dtp-preview-value"></span>
            </div>
        </div>
    </div>
</div>