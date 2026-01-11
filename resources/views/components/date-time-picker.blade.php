{{-- resources/views/components/date-time-picker.blade.php --}}
@props([
    'name' => 'datetime',
    'value' => '',
    'label' => 'Select Date & Time',
    'id' => null,
    'mode' => 'datetime', // 'date' | 'datetime'
    'displayFormat' => null,
    'submitFormat' => null,
])

@php
    $uniqueId = $id ?? 'dtp_' . uniqid();
    $showTime = $mode !== 'date';
    $resolvedDisplayFormat = $displayFormat ?? ($showTime ? 'DD MMM, YYYY hh:mm a' : 'DD MMM, YYYY');
    $resolvedSubmitFormat = $submitFormat ?? ($showTime ? 'YYYY-MM-DD HH:mm' : 'YYYY-MM-DD');
@endphp

@once
    <script>
        window.dateTimePickerData = function(uniqueId, name, initialValue, config) {
            const cfg = config || {};
            const mode = cfg.mode || 'datetime';
            const displayFormat = cfg.displayFormat || (mode === 'date' ? 'DD MMM, YYYY' : 'DD MMM, YYYY hh:mm a');
            const submitFormat = cfg.submitFormat || (mode === 'date' ? 'YYYY-MM-DD' : 'YYYY-MM-DD HH:mm');

            return {
                name: name,
                open: false,
                displayValue: '',
                submitValue: '',
                preview: '',
                mode: mode,
                displayFormat: displayFormat,
                submitFormat: submitFormat,
                state: {
                    year: new Date().getFullYear(),
                    month: new Date().getMonth(),
                    day: new Date().getDate(),
                    hour: 12,
                    minute: 0,
                    ampm: 'AM'
                },
                isInitializing: false,
                wheels: {},
                wheelMeta: {},
                ITEM_HEIGHT: 30,
                REPEAT_COUNT: 3,
                MONTHS: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                DAYS: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],

                init() {
                    const parsed = this.parseInitialValue(initialValue);
                    if (parsed) {
                        this.setFromDate(parsed);
                    } else {
                        this.initState();
                    }
                    this.updatePreview();
                    this.displayValue = this.preview;
                    this.submitValue = this.formatFromState(this.submitFormat);

                    this.$nextTick(() => {
                        this.setupWheels();
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
                    this.state.minute = Math.round(now.getMinutes() / 15) * 15;
                    if (this.state.minute === 60) this.state.minute = 45;
                },

                // Normalize any incoming date string into a Date we can seed the wheels with.
                parseInitialValue(value) {
                    if (!value) return null;

                    if (typeof moment === 'function') {
                        const preferredFormats = [
                            this.displayFormat,
                            this.submitFormat,
                        ].filter(Boolean);

                        const formats = [
                            ...preferredFormats,
                            'dddd, YYYY-MMM-DD hh:mm A',
                            'ddd, YYYY-MMM-DD hh:mm A',
                            'YYYY-MMM-DD hh:mm A',
                            'DD MMM, YYYY hh:mm a',
                            'DD MMM, YYYY hh:mm A',
                            'YYYY-MM-DD',
                            'YYYY-M-D',
                            'YYYY-MM-DD HH:mm',
                            'YYYY-MM-DD H:m',
                            'YYYY-M-D H:m',
                            'YYYY-MM-DDTHH:mm',
                            moment.ISO_8601,
                        ];

                        let m = moment(value, formats, true);
                        if (!m.isValid()) m = moment(value);
                        if (m.isValid()) return m.toDate();
                    }

                    const d = new Date(String(value).includes('T') ? value : `${value}T00:00:00`);
                    if (!Number.isNaN(d.getTime())) return d;
                    return null;
                },

                setFromDate(date) {
                    this.state.year = date.getFullYear();
                    this.state.month = date.getMonth();
                    this.state.day = date.getDate();

                    const h24 = date.getHours();
                    this.state.ampm = h24 >= 12 ? "PM" : "AM";
                    this.state.hour = h24 % 12 || 12;

                    const minute = date.getMinutes();
                    this.state.minute = Math.round(minute / 15) * 15;
                    if (this.state.minute === 60) this.state.minute = 45;
                },

                getSelectedDate() {
                    const hour24 = this.mode === 'date'
                        ? 0
                        : (this.state.ampm === 'PM'
                            ? (this.state.hour % 12) + 12
                            : (this.state.hour % 12));
                    return new Date(this.state.year, this.state.month, this.state.day, hour24, this.state.minute, 0, 0);
                },

                formatDate(date, format) {
                    if (typeof moment === 'function') {
                        return moment(date).format(format);
                    }

                    const day = String(date.getDate()).padStart(2, '0');
                    const month = this.MONTHS[date.getMonth()];
                    const year = date.getFullYear();

                    if (format === 'DD MMM, YYYY') return `${day} ${month}, ${year}`;
                    if (format === 'YYYY-MM-DD') return `${year}-${String(date.getMonth() + 1).padStart(2, '0')}-${day}`;

                    const hours24 = date.getHours();
                    const ampm = hours24 >= 12 ? 'pm' : 'am';
                    const hour = String(hours24 % 12 || 12).padStart(2, '0');
                    const minute = String(date.getMinutes()).padStart(2, '0');

                    if (format === 'DD MMM, YYYY hh:mm a') return `${day} ${month}, ${year} ${hour}:${minute} ${ampm}`;
                    if (format === 'YYYY-MM-DD HH:mm') return `${year}-${String(date.getMonth() + 1).padStart(2, '0')}-${day} ${String(hours24).padStart(2, '0')}:${minute}`;

                    return date.toLocaleString();
                },

                formatFromState(format) {
                    return this.formatDate(this.getSelectedDate(), format);
                },

                beginInit() {
                    this.isInitializing = true;
                    Object.values(this.wheels).forEach((wheel) => {
                        if (wheel._dtpScrollTimeout) {
                            clearTimeout(wheel._dtpScrollTimeout);
                            wheel._dtpScrollTimeout = null;
                        }
                        wheel.style.scrollSnapType = 'none';
                    });
                },

                finishInit() {
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            this.isInitializing = false;
                            Object.values(this.wheels).forEach((wheel) => {
                                wheel.style.scrollSnapType = 'y mandatory';
                                this.updateWheelStyles(wheel);
                            });
                            this.updatePreview();
                        });
                    });
                },

                // Build all wheels, then snap them to the current state after DOM paint.
                setupWheels() {
                    const container = this.$el.querySelector('.picker-container-' + uniqueId);
                    if (!container) return;

                    const wheels = container.querySelectorAll('.picker-wheel');

                    this.beginInit();
                    wheels.forEach(wheel => {
                        const type = wheel.dataset.type;
                        this.buildWheel(wheel, type);
                        this.wheels[type] = wheel;
                        wheel.style.scrollSnapType = 'none';
                        this.attachWheelEvents(wheel, type);
                    });

                    setTimeout(() => {
                        this.setWheelValue('year', this.state.year, true);
                        this.setWheelValue('month', this.state.month, true);
                        this.setWheelValue('day', this.state.day, true);
                        this.setWheelValue('hour', this.state.hour, true);
                        this.setWheelValue('minute', this.state.minute, true);
                        this.setWheelValue('ampm', this.state.ampm, true);
                        this.finishInit();
                    }, 50);
                },

                createItem(label, value) {
                    const li = document.createElement('li');
                    li.className = 'picker-item';
                    li.textContent = typeof label === 'number' ? String(label).padStart(2, '0') : label;
                    li.dataset.value = value;
                    return li;
                },

                getDaysInMonth(year, month) {
                    return new Date(year, month + 1, 0).getDate();
                },

                getWheelItems(type) {
                    const currentYear = new Date().getFullYear();

                    if (type === 'year') {
                        const baseYear = this.state.year || currentYear;
                        const years = [];
                        for (let y = baseYear - 10; y <= baseYear + 10; y++) {
                            years.push({ label: y, value: y });
                        }
                        return years;
                    }
                    if (type === 'month') {
                        return this.MONTHS.map((m, i) => ({ label: m, value: i }));
                    }
                    if (type === 'day') {
                        const days = this.getDaysInMonth(this.state.year, this.state.month);
                        return Array.from({ length: days }, (_, i) => ({ label: i + 1, value: i + 1 }));
                    }
                    if (type === 'hour') {
                        const hours = [];
                        for (let h = 1; h <= 12; h++) hours.push({ label: h, value: h });
                        return hours;
                    }
                    if (type === 'minute') {
                        const minutes = [];
                        for (let m = 0; m < 60; m += 15) minutes.push({ label: m, value: m });
                        return minutes;
                    }
                    if (type === 'ampm') {
                        return [
                            { label: 'AM', value: 'AM' },
                            { label: 'PM', value: 'PM' }
                        ];
                    }
                    return [];
                },

                buildWheel(wheel, type) {
                    const list = wheel.querySelector('ul');
                    if (!list) return;

                    const items = this.getWheelItems(type);
                    if (type === 'day' && items.length && this.state.day > items.length) {
                        this.state.day = items.length;
                    }

                    list.innerHTML = '';
                    if (!items.length) return;

                    const repeatCount = type === 'ampm' ? 1 : this.REPEAT_COUNT;
                    for (let r = 0; r < repeatCount; r++) {
                        items.forEach(item => list.appendChild(this.createItem(item.label, item.value)));
                    }

                    this.wheelMeta[type] = { size: items.length, items: items, repeat: repeatCount };
                    wheel._dtpItems = Array.from(list.querySelectorAll('.picker-item'));
                    wheel._dtpLastStyledIndex = null;
                },

                // Rebuild the day wheel when month/year changes without jumping the user's segment.
                updateDayWheel(wheel) {
                    const target = wheel || this.wheels.day;
                    if (!target) return;
                    const max = this.getDaysInMonth(this.state.year, this.state.month);
                    const meta = this.wheelMeta.day;
                    if (this.state.day > max) this.state.day = max;
                    if (meta && meta.size === max) {
                        if (this.state.day > max) {
                            this.setWheelValue('day', this.state.day, true);
                        }
                        return;
                    }

                    const oldIndex = meta ? Math.round(target.scrollTop / this.ITEM_HEIGHT) : 0;
                    const oldNormalized = meta ? ((oldIndex % meta.size) + meta.size) % meta.size : this.state.day - 1;
                    const oldSegment = meta && meta.repeat ? Math.floor(oldIndex / meta.size) : 1;

                    this.buildWheel(target, 'day');
                    const newMeta = this.wheelMeta.day;
                    if (!newMeta || !newMeta.size) return;

                    let newNormalized = Math.min(oldNormalized, newMeta.size - 1);
                    if (this.state.day > max) {
                        this.state.day = max;
                        newNormalized = max - 1;
                    }
                    const segment = newMeta.repeat === 1 ? 0 : Math.min(Math.max(oldSegment, 0), newMeta.repeat - 1);
                    const targetIndex = newMeta.repeat === 1 ? newNormalized : newNormalized + (segment * newMeta.size);
                    target.scrollTop = targetIndex * this.ITEM_HEIGHT;
                    this.updateWheelStyles(target);
                },

                attachWheelEvents(wheel, type) {
                    let isDragging = false;
                    let startY = 0;
                    let startScrollTop = 0;
                    let dragMoved = false;

                    const mousedown = (e) => {
                        isDragging = true;
                        dragMoved = false;
                        startY = e.pageY;
                        startScrollTop = wheel.scrollTop;
                        wheel.style.scrollSnapType = 'none';
                    };

                    const mousemove = (e) => {
                        if (!isDragging) return;
                        const delta = startY - e.pageY;
                        if (Math.abs(delta) > 4) dragMoved = true;
                        wheel.scrollTop = startScrollTop + delta;
                    };

                    const mouseup = () => {
                        if (isDragging) {
                            isDragging = false;
                            wheel.style.scrollSnapType = 'y mandatory';
                            this.handleScrollEnd(type, wheel);
                            if (dragMoved) {
                                setTimeout(() => {
                                    dragMoved = false;
                                }, 0);
                            }
                        }
                    };

                    wheel.addEventListener('mousedown', mousedown);
                    document.addEventListener('mousemove', mousemove);
                    document.addEventListener('mouseup', mouseup);

                    const scheduleWheelStyleUpdate = () => {
                        if (wheel._dtpRaf) return;
                        wheel._dtpRaf = requestAnimationFrame(() => {
                            wheel._dtpRaf = null;
                            this.updateWheelStyles(wheel);
                        });
                    };

                    wheel.addEventListener('scroll', () => {
                        if (this.isInitializing) return;
                        if (wheel._dtpScrollTimeout) {
                            clearTimeout(wheel._dtpScrollTimeout);
                            wheel._dtpScrollTimeout = null;
                        }
                        scheduleWheelStyleUpdate();

                        wheel._dtpScrollTimeout = setTimeout(() => {
                            if (!isDragging) this.handleScrollEnd(type, wheel);
                        }, 140);
                    }, { passive: true });

                    wheel.addEventListener('click', (e) => {
                        const item = e.target.closest('.picker-item');
                        if (!item) return;
                        if (dragMoved) {
                            dragMoved = false;
                            return;
                        }
                        this.scrollToItem(type, wheel, item);
                    });
                },

                recenterWheel(type, wheel) {
                    const meta = this.wheelMeta[type];
                    if (!meta || !meta.size || meta.repeat === 1) return 0;
                    const segment = meta.size * this.ITEM_HEIGHT;
                    let offset = 0;

                    if (wheel.scrollTop < segment) {
                        wheel.scrollTop += segment;
                        offset = segment;
                    } else if (wheel.scrollTop >= segment * 2) {
                        wheel.scrollTop -= segment;
                        offset = -segment;
                    }

                    return offset;
                },

                scrollToItem(type, wheel, item) {
                    const meta = this.wheelMeta[type];
                    if (!meta || !meta.size) return;
                    const items = Array.from(wheel.querySelectorAll('.picker-item'));
                    const clickedIndex = items.indexOf(item);
                    if (clickedIndex === -1) return;

                    if (meta.repeat === 1) {
                        wheel.scrollTo({ top: clickedIndex * this.ITEM_HEIGHT, behavior: 'smooth' });
                        return;
                    }

                    const baseIndex = clickedIndex % meta.size;
                    const currentIndex = Math.round(wheel.scrollTop / this.ITEM_HEIGHT);
                    const candidates = [
                        baseIndex,
                        baseIndex + meta.size,
                        baseIndex + meta.size * 2
                    ];
                    let targetIndex = candidates[0];

                    candidates.forEach(candidate => {
                        if (Math.abs(candidate - currentIndex) < Math.abs(targetIndex - currentIndex)) {
                            targetIndex = candidate;
                        }
                    });

                    wheel.scrollTo({ top: targetIndex * this.ITEM_HEIGHT, behavior: 'smooth' });
                },

                // Commit the selected wheel item and keep the scroll centered for infinite wheels.
                handleScrollEnd(type, wheel) {
                    if (this.isInitializing) return;
                    const meta = this.wheelMeta[type];
                    if (!meta || !meta.size) return;

                    let index = Math.round(wheel.scrollTop / this.ITEM_HEIGHT);
                    if (meta.repeat === 1) {
                        const maxIndex = meta.size - 1;
                        index = Math.min(Math.max(index, 0), maxIndex);
                        wheel.scrollTop = index * this.ITEM_HEIGHT;
                    } else {
                        const normalizedIndex = ((index % meta.size) + meta.size) % meta.size;
                        const centeredIndex = normalizedIndex + meta.size;

                        if (index !== centeredIndex) {
                            wheel.scrollTop = centeredIndex * this.ITEM_HEIGHT;
                            index = centeredIndex;
                        }
                    }

                    const items = wheel._dtpItems || wheel.querySelectorAll('.picker-item');
                    const item = items[index];
                    if (!item) return;

                    const val = item.dataset.value;
                    const oldMonth = this.state.month;
                    const oldYear = this.state.year;

                    this.state[type] = (type === 'ampm') ? val : parseInt(val, 10);

                    if ((type === 'month' || type === 'year') && (oldMonth !== this.state.month || oldYear !== this.state.year)) {
                        this.updateDayWheel(this.wheels.day);
                    }
                    this.updateWheelStyles(wheel);
                    this.updatePreview();
                },

                updateWheelStyles(wheel) {
                    const items = wheel._dtpItems || Array.from(wheel.querySelectorAll('.picker-item'));
                    wheel._dtpItems = items;
                    if (!items.length) return;

                    const index = Math.round(wheel.scrollTop / this.ITEM_HEIGHT);
                    const previousIndex = (wheel._dtpLastStyledIndex ?? index);
                    if (wheel._dtpLastStyledIndex === index) return;
                    wheel._dtpLastStyledIndex = index;

                    const scaleByDiff = [1, 0.94, 0.88, 0.84, 0.8];
                    const opacityByDiff = [1, 0.62, 0.38, 0.22, 0.14];
                    const range = 10;
                    const start = Math.max(0, Math.min(previousIndex, index) - range);
                    const end = Math.min(items.length - 1, Math.max(previousIndex, index) + range);

                    for (let i = start; i <= end; i++) {
                        const item = items[i];
                        if (!item) continue;
                        const diff = Math.abs(i - index);
                        const capped = diff > 4 ? 4 : diff;

                        item.classList.toggle('selected', i === index);
                        item.classList.toggle('near', diff > 0 && diff <= 3);
                        item.style.opacity = String(opacityByDiff[capped]);
                        item.style.transform = `translateZ(0) scale(${scaleByDiff[capped]})`;
                    }
                },

                setWheelValue(type, value, immediate = false) {
                    const wheel = this.wheels[type];
                    const meta = this.wheelMeta[type];
                    if (!wheel || !meta || !meta.size) return;
                    const baseIndex = meta.items.findIndex(it => it.value == value);
                    if (baseIndex !== -1) {
                        const targetIndex = meta.repeat === 1 ? baseIndex : baseIndex + meta.size;
                        if (immediate) {
                            wheel.scrollTop = targetIndex * this.ITEM_HEIGHT;
                            this.updateWheelStyles(wheel);
                        } else {
                            wheel.scrollTo({ top: targetIndex * this.ITEM_HEIGHT, behavior: 'smooth' });
                        }
                    }
                },

                updatePreview() {
                    this.preview = this.formatFromState(this.displayFormat);
                },

                syncStateFromInput() {
                    const candidate = this.displayValue || this.submitValue || '';
                    const parsed = this.parseInitialValue(candidate) || this.parseInitialValue(this.submitValue);
                    if (parsed) {
                        this.setFromDate(parsed);
                        this.updatePreview();
                        return true;
                    }
                    return false;
                },

                ensureYearWheel() {
                    const meta = this.wheelMeta.year;
                    if (!this.wheels.year) return;
                    if (!meta || !meta.items || !meta.items.some(it => it.value == this.state.year)) {
                        this.buildWheel(this.wheels.year, 'year');
                    }
                },

                cancel() {
                    this.open = false;
                    document.body.style.overflow = '';
                },

                // Finalize selections and write the submit value before closing.
                confirm() {
                    const wasInitializing = this.isInitializing;
                    this.isInitializing = false;

                    // Ensure wheel selections are committed even if the user taps "Done"
                    // before the scroll debounce finishes (common on touch/inertia scroll).
                    ['year', 'month', 'day', 'hour', 'minute', 'ampm'].forEach((type) => {
                        const wheel = this.wheels[type];
                        if (wheel) this.handleScrollEnd(type, wheel);
                    });

                    this.isInitializing = wasInitializing;
                    this.displayValue = this.preview;
                    this.submitValue = this.formatFromState(this.submitFormat);
                    this.open = false;
                    document.body.style.overflow = '';
                },

                openModal() {
                    this.open = true;
                    this.beginInit();
                    this.syncStateFromInput();
                    this.ensureYearWheel();
                    this.updateDayWheel(this.wheels.day);
                    this.$nextTick(() => {
                        this.setWheelValue('year', this.state.year, true);
                        this.setWheelValue('month', this.state.month, true);
                        this.setWheelValue('day', this.state.day, true);
                        this.setWheelValue('hour', this.state.hour, true);
                        this.setWheelValue('minute', this.state.minute, true);
                        this.setWheelValue('ampm', this.state.ampm, true);
                        this.finishInit();
                    });
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
            font-size: 17px;
            font-weight: 500;
            margin-bottom: 8px;
            color: inherit;
        }

        .dtp-input {
            cursor: pointer;
            font-size: 0.9em;
        }

        .dtp-input:focus {
            outline: none;
        }

        .dtp-modal-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(2px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dtp-modal-content {
            background-color: #111827;
            width: 100%;
            max-width: 480px;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            position: relative;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "SF Pro Display", "Helvetica Neue", Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        .dtp-header {
            background-color: #a7a7a7;
            border-bottom: 1px solid #374151;
            padding: 12px 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50px;
            border-radius: 20px 20px 0 0 ;
        }

        .dtp-cancel-btn-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: black;
            cursor: pointer;
            background: none;
            border: none;
        }

        .dtp-cancel-btn-icon:hover {
            color: #fff;
        }

        .dtp-done-btn {
            background-color: #3b82f6;
            color: white;
            border: none;
            font-size: 17px;
            font-weight: normal;
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 8px;
            width: 50%;
        }

        .dtp-header-title {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .dtp-picker-section {
            padding: 16px;
            background-color: #dedede;
        }

        .dtp-section-labels {
            display: flex;
            width: 100%;
            margin-bottom: 8px;
        }

        .dtp-section-label {
            flex: 1;
            text-align: center;
            font-size: 12px;
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
            background-color: white;
            border-radius: 12px;
            padding: 4px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            height: 192px;
            position: relative;
            overflow: hidden;
            border: 1px solid #334155;
            isolation: isolate;
        }

        /* Prevent "first open" wheel bounce/flash by hiding until positioned */
        .dtp-picker-container.dtp-initializing {
            opacity: 0;
            pointer-events: none;
        }

        .dtp-picker-container::before,
        .dtp-picker-container::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            height: 40px;
            pointer-events: none;
            z-index: 9;
            border-radius: inherit;
        }

        .dtp-picker-container::before {
            top: 0;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0));
        }

        .dtp-picker-container::after {
            bottom: 0;
            background: linear-gradient(to top, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0));
        }

        .picker-highlight {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 30px;
            margin-top: -15px;
            pointer-events: none;
            /*border-top: 1px solid rgba(0, 0, 0, 0.08);*/
            /*border-bottom: 1px solid rgba(0, 0, 0, 0.08);*/
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.03), rgba(0, 0, 0, 0.08), rgba(0, 0, 0, 0.03));
            z-index: 10;
        }

        .picker-wheel {
            height: 100%;
            overflow-y: scroll;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-snap-type: y mandatory;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
            touch-action: pan-y;
            cursor: grab;
            contain: layout paint;
        }

        .picker-wheel::-webkit-scrollbar {
            display: none;
        }

        .picker-wheel:active {
            cursor: grabbing;
        }

        .picker-wheel[data-type="year"] { flex: 1.3; }
        .picker-wheel[data-type="month"] { flex: 0.7; }
        .picker-wheel[data-type="day"] { flex: 0.7; }
        .picker-wheel[data-type="hour"] { flex: 0.8; }
        .picker-wheel[data-type="minute"] { flex: 0.8; }
        .picker-wheel[data-type="ampm"] { flex: 1.1; }

        .picker-wheel ul {
            list-style: none;
            margin: 0;
            padding: 81px 0;
            text-align: center;
        }

        .picker-item {
            height: 30px;
            line-height: 30px;
            color: #6b7280;
            font-size: 19px;
            font-weight: 400;

            scroll-snap-align: center;
            scroll-snap-stop: always;
            user-select: none;
            white-space: nowrap;
            cursor: pointer;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.01em;
            will-change: transform, opacity;
        }

        .picker-item.selected {
            color: #111827;
            font-weight: 400;
            font-size: 19px;
        }

        .picker-item.near {
            color: #4b5563;
        }

        .dtp-divider {
            width: 1px;
            height: 50%;
            background: rgb(62 62 62);
            align-self: center;
            margin: 0 12px;
            flex-shrink: 0;
        }

        .dtp-time-separator {
            display: flex;
            align-items: center;
            font-weight: bold;
            color: white;
            font-size: 17px;
            margin: 0 -10px;
            flex-shrink: 0;
        }

        .dtp-preview-section {
            background-color: #dedede;
            padding: 16px 16px 24px;
            text-align: center;
            border-radius: 0 0 20px 20px;
        }

        .dtp-preview-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .dtp-preview-value {
            font-size: 1.2em;

            font-weight: 600;
            color: #3e3e3e;
        }


        [x-cloak] {
            display: none !important;
        }

        @media (min-width: 640px) {
            .dtp-modal-backdrop {
                align-items: center;
            }

            .dtp-modal-content {
                border-radius: 16px;
            }
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Helvetica Neue", Arial, sans-serif;

        }
    </style>
@endonce

<div
        x-data='dateTimePickerData(
            @json($uniqueId),
            @json($name),
            @json($value),
            @json(['mode' => $mode, 'displayFormat' => $resolvedDisplayFormat, 'submitFormat' => $resolvedSubmitFormat])
        )'
        class="dtp-wrapper"
>
    @if($label)
        <label class="dtp-label">{{ $label }}</label>
    @endif

    <input
            type="text"
            id="{{ $uniqueId }}"
            autocomplete="off"
            placeholder="{{ $label }}"
            readonly
            @click="openModal()"
            @keydown.enter.prevent="openModal()"
            @keydown.space.prevent="openModal()"
            x-model="displayValue"
            value="{{ $value }}"
            {{ $attributes->merge(['class' => 'form-control dtp-input']) }}
    />

    <input type="hidden" name="{{ $name }}" value="{{ $value }}" x-model="submitValue" />

    {{-- Modal --}}
    <div
            x-show="open"
            x-cloak
            @click.self="confirm()"
            class="dtp-modal-backdrop"
    >
        <div
                @click.stop
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-full"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="dtp-modal-content"

        >
            <button @click="cancel()" type="button" class="dtp-cancel-btn-icon">&times;</button>
            {{-- Header --}}
            <div class="dtp-header">
                <span x-text="preview" class="dtp-preview-value"></span>
            </div>

            {{-- Picker Container --}}
            <div class="dtp-picker-section">
                <div class="dtp-section-labels">
                    <div class="dtp-section-label">Date</div>
                    @if($showTime)
                        <div class="dtp-section-spacer"></div>
                        <div class="dtp-section-label">Time</div>
                    @endif
                </div>

                <div class="dtp-picker-container picker-container-{{ $uniqueId }}" :class="{ 'dtp-initializing': isInitializing }">
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

                    @if($showTime)
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
                    @endif
                </div>
            </div>

            {{-- Preview --}}
            <div class="dtp-preview-section">
                <button @click="confirm()" type="button" class="dtp-done-btn">Done</button>
            </div>
        </div>
    </div>
</div>
