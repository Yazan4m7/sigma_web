{{--
    REPEATS REPORT - RIGHT SIDE TOGGLES DESIGN
    ==========================================
    This file contains the styling for the toggles positioned on the far right
    as secondary/optional filters without labels.

    Usage: Copy this CSS and HTML structure when you want the right-side toggle design
--}}

{{-- CSS STYLING --}}
<style>
/* Connected Toggle Buttons - Rounded Rectangle Style */
.connected-toggle-container {
    display: inline-flex;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    overflow: hidden;
    background: #fff;
    min-width: 140px;
    width: 140px;
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
    min-width: 140px;
    width: 140px;
    justify-content: space-between;
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

{{-- HTML STRUCTURE --}}
{{--
    Place this in your filters row:
    - Use col-auto at the end of the row
    - Spacer column pushes toggles to far right
--}}

<!-- In your filter row, before closing </div> -->
<!-- Spacer to push toggles to the right -->
<div class="col"></div>

<!-- Toggles Column - Far Right -->
<div class="col-auto">
    <div style="display: flex; flex-direction: column; align-items: flex-end;">
        <!-- View Mode Toggle (Units/Cases) - Aligned with top filters -->
        <div style="margin-top: 28px;">
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

        <!-- Display Toggle (Count/Percentage) - Spaced below -->
        <div style="margin-top: 45px;">
            <div class="modern-toggle-container">
                <span class="modern-toggle-label {{ $countOrPercentage ? 'active' : '' }}">Count</span>
                <div class="modern-toggle-switch">
                    <input type="checkbox" id="toggle-checkbox" {{ !$countOrPercentage ? 'checked' : '' }}>
                    <label for="toggle-checkbox" class="modern-toggle-slider"></label>
                </div>
                <span class="modern-toggle-label {{ !$countOrPercentage ? 'active' : '' }}">%</span>
                <input type="hidden" name="countOrPercentageToggle" id="countOrPercentageToggle" value="{{ $countOrPercentage ? '1' : '0' }}">
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT --}}
{{--
    Add this to your scripts section:
    - Handles toggle functionality
    - Disables view mode when in percentage mode
--}}
<script>
// Enable/disable view mode toggle based on display mode
function updateViewModeToggle() {
    const toggle = document.getElementById('toggle-checkbox');
    const viewModeContainer = document.getElementById('view-mode-container');

    if (toggle && viewModeContainer) {
        if (toggle.checked) {
            // Percentage mode - disable view mode toggle
            viewModeContainer.classList.add('disabled');
            $('#units-toggle, #cases-toggle').prop('disabled', true);
        } else {
            // Count mode - enable view mode toggle
            viewModeContainer.classList.remove('disabled');
            $('#units-toggle, #cases-toggle').prop('disabled', false);
        }
    }
}

// Initialize on page load
$(document).ready(function() {
    updateViewModeToggle();

    // Update on toggle change
    $('#toggle-checkbox').on('change', function() {
        updateViewModeToggle();
    });
});
</script>

{{--
    KEY FEATURES:
    - Toggles positioned at far right as secondary filters
    - No labels (clean minimalist design)
    - 140px fixed width for both toggles
    - 28px top margin aligns with dropdown inputs
    - 45px spacing between toggles
    - Auto-disables view mode in percentage mode
    - Dark teal gradient for active state
    - White text on active buttons for clarity
--}}
