@extends('layouts.app', ['pageSlug' => 'devices'])

@php
    // Load global configuration for device styling
    $deviceConfig = config('app_config.device_images', [
        'width' => '100%',
        'max_width' => '180px',
        'height' => 'auto',
        'padding' => '10px',
        'border_radius' => '8px',
        'hover_effect' => true,
        'background' => 'transparent',
    ]);
@endphp

@push('css')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Include existing stylesheets from operations dashboard -->
    <link href="{{ asset('assets') }}/css/ysh-custom-css/dialog.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/ysh-custom-css/OperationsDashboardStyling.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/active-cases.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/waiting-dialog.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/v3styles.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/css/devices-page.css" rel="stylesheet">

    <style>

        .sigma-workflow-dialog {
            max-width: none !important;
            width: auto !important;
            min-width: 600px !important;
            /* Minimum width for proper machine display */
        }
        /* Main devices page container with enhanced background */
        .devices-page-container {
            padding: 20px;
            background:
                linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%),
                radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(16, 185, 129, 0.03) 0%, transparent 50%);
            min-height: 100vh;
        }

        .devices-page-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .devices-page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .devices-page-subtitle {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 20px;
        }

        /* Configuration Panel - fully hidden when not active */
        .config-panel {
            position: fixed;
            top: 60px;
            right: -320px;
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.1),
                0 8px 25px rgba(0, 0, 0, 0.08),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            padding: 24px;
            width: 320px;
            max-height: calc(100vh - 80px);
            overflow-y: auto;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.6);
            backdrop-filter: blur(20px);
        }

        .config-panel.active {
            right: 20px;
            opacity: 1;
            visibility: visible;
        }

        .config-toggle {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
            transition: all 0.3s ease;
            z-index: 999;
        }

        .config-toggle:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        .config-section {
            margin-bottom: 24px;
            padding: 18px;
            background: rgba(248, 250, 252, 0.6);
            border-radius: 12px;
            border: 1px solid rgba(226, 232, 240, 0.4);
            transition: all 0.2s ease;
        }

        .config-section:hover {
            background: rgba(248, 250, 252, 0.8);
            border-color: rgba(59, 130, 246, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .config-section:last-child {
            margin-bottom: 0;
        }

        .config-label {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
            display: block;
            font-size: 14px;
            letter-spacing: 0.025em;
        }

        .config-option {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 6px 0;
        }

        .config-option:last-child {
            margin-bottom: 0;
        }

        .config-option input[type="checkbox"],
        .config-option input[type="radio"] {
            margin-right: 10px;
            width: 16px;
            height: 16px;
            accent-color: #3b82f6;
        }

        .config-option input[type="number"] {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 13px;
            background: white;
            transition: all 0.2s ease;
        }

        .config-option input[type="number"]:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .config-option label {
            font-size: 13px;
            color: #4b5563;
            font-weight: 500;
            cursor: pointer;
        }

        /* Configuration panel header */
        .config-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px solid rgba(59, 130, 246, 0.1);
        }

        .config-header h4 {
            margin: 0;
            color: #1e293b;
            font-weight: 700;
            font-size: 18px;
            letter-spacing: -0.025em;
        }

        .config-header-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        /* Custom scrollbar for configuration panel */
        .config-panel::-webkit-scrollbar {
            width: 6px;
        }

        .config-panel::-webkit-scrollbar-track {
            background: rgba(248, 250, 252, 0.5);
            border-radius: 3px;
        }

        .config-panel::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 3px;
        }

        .config-panel::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
        }

        /* Device grid layout with grouping support */
        .devices-grid {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 0;
        }

        /* #devicesContent styles moved to external CSS file */

        .devices-grid.grouped{
            display: block !important;
        }

        /* Category groups */
        .device-category-group {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: 0 6px 30px rgba(30, 60, 90, 0.08);
            border: 2px solid #e0e6ed;
            transition: all 0.3s ease;
        }

        .device-category-group:hover {
            border-color: #b3c6e0;
            box-shadow: 0 8px 40px rgba(30, 60, 90, 0.12);
        }

        .category-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f4f8;
        }

        .category-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            font-size: 24px;
            font-weight: bold;
            color: white;
        }

        .category-milling { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .category-3dprinting { background: linear-gradient(135deg, #10b981, #059669); }
        .category-sintering { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .category-pressing { background: linear-gradient(135deg, #ef4444, #dc2626); }

        .category-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .category-devices {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(var(--device-size, 200px), 1fr));
            gap: 20px;
        }

        /* Individual device card with enhanced modern styling */
        .device-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            box-shadow:
                0 8px 32px rgba(30, 60, 90, 0.08),
                0 2px 16px rgba(30, 60, 90, 0.04),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(230, 236, 245, 0.6);
            width: 100%;
            padding: 16px 15px 12px 15px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: var(--device-height, 160px);
            backdrop-filter: blur(10px);
        }

        /* Operations dashboard device states - exact replication */
        .device-card.clickable {
            cursor: pointer;
        }

        .device-card.clickable:hover {
            transform: translateY(-5px) scale(1.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .device-card.inactive {
            cursor: default;
            filter: grayscale(100%);
        }

        .device-card.inactive:hover {
            transform: none;
        }

        .device-card.inactive .device-image {
            filter: grayscale(100%) blur(0.5px);
        }

        /* Waiting only devices - clickable but grayscale */
        .device-card.waiting-only {
            cursor: pointer;
        }

        .device-card.waiting-only .device-image {
            filter: grayscale(100%) blur(0.5px);
        }

        .device-card.waiting-only:hover {
            transform: none;
        }

        /* Device image wrapper for badge positioning */
        .device-image-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            margin-bottom: 8px;
        }

        /* Device image */
        .device-image {
            width: 100%;
            height: auto;
            max-width: var(--image-size, 150px);
            max-height: var(--image-height, 120px);
            object-fit: contain;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Device image hover effects */
        .device-card.clickable:hover .device-image {
            transform: translateY(-3px) scale(1.05);
        }

        .device-image.grayscale {
            filter: grayscale(100%);
        }

        /* Device type and name */
        .device-type {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .device-name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-top: 4px;
            line-height: 1.1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
        }

        /* Badge positioning variants */
        .device-badges {
            display: flex;
            flex-direction: row;
            gap: 6px;
            z-index: 200;
            position: absolute;
            bottom: -24px;
            right: -27px;
            justify-content: flex-end;
        }

        /* Badge styling */
        .device-badge {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(30, 60, 90, 0.08);
            transition: transform 0.2s ease;
        }

        /* Remove hover effect from badges */
        .device-badge:hover {
            transform: none;
        }

        .device-badge-blue {
            background: linear-gradient(135deg, #3b82f6 60%, #2563eb 100%);
        }

        .device-badge-red {
            background: linear-gradient(135deg, #dc2626 60%, #b91c1c 100%);
        }

        /* Size variants - compact heights, focus on image size */
        .size-small {
            --device-size: 160px;
            --device-height: 140px;
            --image-size: 80px;
            --image-height: 60px;
        }

        .size-medium {
            --device-size: 200px;
            --device-height: 160px;
            --image-size: 110px;
            --image-height: 85px;
        }

        .size-large {
            --device-size: 260px;
            --device-height: 200px;
            --image-size: 140px;
            --image-height: 110px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .config-panel {
                min-width: 250px;
                padding: 15px;
            }

            .devices-grid {
                gap: 20px;
                padding: 15px;
            }

            .devices-page-title {
                font-size: 2rem;
            }

            .category-devices {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }
        }

        /* No devices message */
        .no-devices {
            grid-column: 1 / -1;
            text-align: center;
            color: #6c757d;
            font-size: 1.2rem;
            padding: 40px;
            background: white;
            border-radius: 12px;
            border: 2px dashed #e0e6ed;
        }

        /* Loading and animation states */
        .device-card.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .device-card.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .device-card.clicked {
            animation: deviceClickFeedback 0.2s ease-out;
        }

        @keyframes deviceClickFeedback {
            0% { transform: scale(1); }
            50% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }
    </style>
@endpush
        
@section('content')
<div class="devices-page-container">
    <div class="devices-page-header">
        <h1 class="devices-page-title">DEVICES</h1>

        <!-- Configuration Toggle Button -->
        <button class="config-toggle" onclick="toggleConfigPanel()">
            <i class="fa-solid fa-cog"></i>
        </button>

        <!-- Configuration Panel -->
        <div class="config-panel" id="configPanel">
            <div class="config-header">
                <h4>Display Settings</h4>
                <div class="config-header-icon">
                    <i class="fa-solid fa-sliders"></i>
                </div>
            </div>

            <div class="config-section">
                <label class="config-label">Show Category Names</label>
                <div class="config-option">
                    <input type="checkbox" id="showCategoryNames" onchange="updateConfig()">
                    <label for="showCategoryNames">Display category labels</label>
                </div>
            </div>

            <div class="config-section">
                <label class="config-label">Show Device Names</label>
                <div class="config-option">
                    <input type="checkbox" id="showDeviceNames" onchange="updateConfig()">
                    <label for="showDeviceNames">Display device names</label>
                </div>
            </div>

            <div class="config-section">
                <label class="config-label">Badge Position</label>
                <div class="config-option">
                    <label for="badgeRight">Right (px):</label>
                    <input type="number" id="badgeRight" value="-27" onchange="updateConfig()" style="width: 60px; margin-left: 8px;">
                </div>
                <div class="config-option">
                    <label for="badgeBottom">Bottom (px):</label>
                    <input type="number" id="badgeBottom" value="-24" onchange="updateConfig()" style="width: 60px; margin-left: 8px;">
                </div>
                <div class="config-option">
                    <label for="badgeGap">Gap between badges (px):</label>
                    <input type="number" id="badgeGap" value="6" min="0" max="20" onchange="updateConfig()" style="width: 60px; margin-left: 8px;">
                </div>
            </div>

            <div class="config-section">
                <label class="config-label">Group by Category</label>
                <div class="config-option">
                    <input type="checkbox" id="groupByCategory" onchange="updateConfig()">
                    <label for="groupByCategory">Group devices by type</label>
                </div>
            </div>

            <div class="config-section">
                <label class="config-label">Sortable Mode</label>
                <div class="config-option">
                    <input type="checkbox" id="sortableMode" onchange="toggleSortableMode()">
                    <label for="sortableMode">Enable drag & drop sorting</label>
                </div>
            </div>

            <div class="config-section">
                <label class="config-label">Image Size</label>
                <div class="config-option">
                    <input type="radio" id="sizeSmall" name="imageSize" value="small" onchange="updateConfig()">
                    <label for="sizeSmall">Small</label>
                </div>
                <div class="config-option">
                    <input type="radio" id="sizeMedium" name="imageSize" value="medium" onchange="updateConfig()">
                    <label for="sizeMedium">Medium</label>
                </div>
                <div class="config-option">
                    <input type="radio" id="sizeLarge" name="imageSize" value="large" onchange="updateConfig()">
                    <label for="sizeLarge">Large</label>
                </div>
            </div>

            <div class="config-section">
                <label class="config-label">Category Font Size</label>
                <div class="config-option">
                    <label for="categoryFontSize">Size (px):</label>
                    <input type="number" id="categoryFontSize" value="12" min="8" max="17" onchange="updateConfig()" style="width: 60px; margin-left: 8px;">
                </div>
            </div>

            <div class="config-section">
                <label class="config-label">Device Name Font Size</label>
                <div class="config-option">
                    <label for="deviceNameFontSize">Size (px):</label>
                    <input type="number" id="deviceNameFontSize" value="14" min="10" max="21" onchange="updateConfig()" style="width: 60px; margin-left: 8px;">
                </div>
            </div>
        </div>
    </div>

    <div class="devices-grid" id="devicesGrid">
        <!-- Devices will be rendered by JavaScript directly into devicesGrid -->
        @if(!isset($devices) || $devices->count() == 0)
            <div class="no-devices">No devices available.</div>
        @endif
    </div>
</div>

<!-- Include waiting dialog components for device-using stages only -->
<x-waiting-dialog title="Select Milling Machine" btnText="NEST" type="milling" :devices="$devices" stageId="2" :showBuildName="true"/>
<x-waiting-dialog title="Select 3D Printer" btnText="SET" type="3dprinting" :devices="$devices" stageId="3" :showBuildName="true"/>
<x-waiting-dialog title="Select Sintering Furnace" btnText="START" type="sintering" :devices="$devices" stageId="4" :showBuildName="false"/>
<x-waiting-dialog title="Select Pressing Furnace" btnText="SET" type="pressing" :devices="$devices" stageId="5" :showBuildName="true"/>

<x-waiting-3dprinting-dialog title="3D Printing Setup" :devices="$devices" stageId="3"/>

<!-- Generate device-specific dialogs for each device -->
@if(isset($devices))
    @foreach ($devices as $device)
        @php
            $deviceType = $device->type;
            $deviceId = $device->id;
        @endphp

        @switch($deviceType)
            @case(2) {{-- Milling --}}
                <x-active-cases-dialog title="Milling Jobs" btnText="COMPLETE" type="milling"
                                       :deviceId="$deviceId"
                                       :isBuilds="false"/>
                @break
            @case(3) {{-- 3D Printing --}}
                <x-active-cases-dialog title="Printer Builds" btnText="COMPLETE" type="3dprinting"
                                       :deviceId="$deviceId"
                                       :isBuilds="true"/>
                @break
            @case(4) {{-- Sintering --}}
                <x-active-cases-dialog title="Sintering Jobs" btnText="COMPLETE" type="sintering"
                                       :deviceId="$deviceId"
                                       :isBuilds="false"/>
                @break
            @case(5) {{-- Pressing --}}
                <x-active-cases-dialog title="Pressing Jobs" btnText="COMPLETE" type="pressing"
                                       :deviceId="$deviceId"
                                       :isBuilds="false"/>
                @break
        @endswitch
    @endforeach
@endif
@endsection

@push('js')
<!-- Include minimal required scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<!-- Device page specific JS without operations dashboard dependencies -->
<script>
    // Prevent operations dashboard auto-initialization
    window.devicesPageMode = true;

    // Essential functions from operations dashboard for device interactions only
    window.handleClick = function(element, deviceId, stageType) {
        console.log(`Device clicked: ${deviceId}, stage: ${stageType}`);

        // Find and trigger the appropriate modal
        const modalSelector = `#${deviceId}casesListDialog`;
        const modal = document.querySelector(modalSelector);

        if (modal) {
            // Use Bootstrap modal if available
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();
            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modal).modal('show');
            } else {
                // Fallback - show modal manually
                modal.style.display = 'block';
                modal.classList.add('show', 'active');
            }
        } else {
            console.warn('Modal not found:', modalSelector);
            alert(`Device ${deviceId} - Modal not available`);
        }
    };

    // Add missing handleDialogBackdropClick function for device dialogs
    window.handleDialogBackdropClick = function(event, deviceId) {
        console.log(`Backdrop clicked for device: ${deviceId}`);
        // Only close if clicking on the backdrop itself, not child elements
        if (event.target === event.currentTarget) {
            closeDeviceDialog(deviceId);
        }
    };

    // Add enhanced closeDeviceDialog function
    window.closeDeviceDialog = function(deviceId) {
        console.log(`closeDeviceDialog called for device: ${deviceId}`);

        // Try all possible dialog IDs
        const possibleDialogIds = [
            `${deviceId}casesListDialog`,
            `${deviceId}-cases-dialog`,
            `device-${deviceId}-dialog`,
            `waiting-milling`,
            `waiting-3dprinting`,
            `waiting-sintering`,
            `waiting-pressing`,
            `waiting-delivery`
        ];

        let dialog = null;
        for (const id of possibleDialogIds) {
            const foundDialog = document.getElementById(id);
            if (foundDialog && (foundDialog.classList.contains('active') || foundDialog.classList.contains('show'))) {
                dialog = foundDialog;
                console.log(`Found active dialog: ${id}`);
                break;
            }
        }

        if (!dialog) {
            console.error(`No active dialog found for device ID: ${deviceId}`);
            // Fallback: close all active modals
            const allActiveModals = document.querySelectorAll('.sigma-workflow-modal.active, .modal.show');
            allActiveModals.forEach(modal => {
                modal.classList.remove('active', 'show');
                modal.style.display = 'none';
            });
            return;
        }

        // Remove focus if dialog contains active element
        if (dialog.contains(document.activeElement)) {
            document.activeElement.blur();
        }

        // Add fade-out animation
        const dialogContent = dialog.querySelector('.sigma-workflow-dialog') || dialog.querySelector('.modal-content');
        if (dialogContent) {
            dialogContent.classList.add('fade-out');
        }

        // Hide dialog after animation completes
        setTimeout(() => {
            dialog.classList.remove('active', 'show');
            dialog.style.display = 'none';
            if (dialogContent) {
                dialogContent.classList.remove('fade-out', 'fade-in');
            }
            // Clean up any overlays
            document.querySelectorAll('.modal-backdrop, .modal-overlay').forEach(backdrop => {
                backdrop.remove();
            });
        }, 300);
    };
</script>

<script>
    // Configuration management
    const defaultConfig = {
        showCategoryNames: true,
        showDeviceNames: false,
        badgeRight: -27,
        badgeBottom: -24,
        badgeGap: 6,
        groupByCategory: false,
        imageSize: 'small',
        categoryFontSize: 12,
        deviceNameFontSize: 14
    };

    let currentConfig = { ...defaultConfig };

    // Device data from PHP
    const devicesData = @json($devices);
    const deviceUnitsCounts = @json($deviceUnitsCounts);
    const deviceTypes = {
        2: 'Milling',
        3: '3D Printing',
        4: 'Sintering',
        5: 'Pressing'
    };
    const stageTypes = {
        '2': 'milling',
        '3': '3dprinting',
        '4': 'sintering',
        '5': 'pressing'
    };

    function loadConfig() {
        const saved = localStorage.getItem('devicesPageConfig');
        if (saved) {
            currentConfig = { ...defaultConfig, ...JSON.parse(saved) };
        }
        applyConfigToUI();
    }

    function saveConfig() {
        localStorage.setItem('devicesPageConfig', JSON.stringify(currentConfig));
    }

    function updateConfig() {
        currentConfig.showCategoryNames = document.getElementById('showCategoryNames').checked;
        currentConfig.showDeviceNames = document.getElementById('showDeviceNames').checked;
        currentConfig.badgeRight = parseInt(document.getElementById('badgeRight').value) || -27;
        currentConfig.badgeBottom = parseInt(document.getElementById('badgeBottom').value) || -24;
        currentConfig.badgeGap = parseInt(document.getElementById('badgeGap').value) || 6;
        currentConfig.groupByCategory = document.getElementById('groupByCategory').checked;
        currentConfig.imageSize = document.querySelector('input[name="imageSize"]:checked')?.value || 'medium';
        currentConfig.categoryFontSize = parseInt(document.getElementById('categoryFontSize').value) || 12;
        currentConfig.deviceNameFontSize = parseInt(document.getElementById('deviceNameFontSize').value) || 14;

        saveConfig();
        updateBadgePositions();
        updateFontSizes();
        renderDevices();

        // Ensure grid layout is maintained after config changes
        setTimeout(() => {
            applyGridLayout();
        }, 10);
    }

    function applyConfigToUI() {
        document.getElementById('showCategoryNames').checked = currentConfig.showCategoryNames;
        document.getElementById('showDeviceNames').checked = currentConfig.showDeviceNames;
        document.getElementById('badgeRight').value = currentConfig.badgeRight;
        document.getElementById('badgeBottom').value = currentConfig.badgeBottom;
        document.getElementById('badgeGap').value = currentConfig.badgeGap;
        document.getElementById('groupByCategory').checked = currentConfig.groupByCategory;
        document.getElementById('categoryFontSize').value = currentConfig.categoryFontSize;
        document.getElementById('deviceNameFontSize').value = currentConfig.deviceNameFontSize;
        document.querySelector(`input[name="imageSize"][value="${currentConfig.imageSize}"]`).checked = true;
    }

    function updateBadgePositions() {
        const style = document.getElementById('dynamicBadgeStyle') || document.createElement('style');
        style.id = 'dynamicBadgeStyle';
        style.textContent = `
            .device-badges {
                right: ${currentConfig.badgeRight}px !important;
                bottom: ${currentConfig.badgeBottom}px !important;
                gap: ${currentConfig.badgeGap}px !important;
            }
        `;
        if (!document.getElementById('dynamicBadgeStyle')) {
            document.head.appendChild(style);
        }
    }

    function updateFontSizes() {
        const style = document.getElementById('dynamicFontStyle') || document.createElement('style');
        style.id = 'dynamicFontStyle';
        style.textContent = `
            .device-type {
                font-size: ${currentConfig.categoryFontSize}px !important;
            }
            .device-name {
                font-size: ${currentConfig.deviceNameFontSize}px !important;
            }
        `;
        if (!document.getElementById('dynamicFontStyle')) {
            document.head.appendChild(style);
        }
    }

    function toggleConfigPanel() {
        const panel = document.getElementById('configPanel');
        panel.classList.toggle('active');
    }

    function getDeviceState(device) {
        const deviceId = device.id;
        const stageId = device.type;

        let activeCount = 0, waitingCount = 0;

        if (stageId == 3) { // 3D Printing - shows builds
            activeCount = deviceUnitsCounts[deviceId]?.activeBuilds || 0;
            waitingCount = deviceUnitsCounts[deviceId]?.waitingBuilds || 0;
        } else { // Other stages - shows units (tooth count)
            activeCount = deviceUnitsCounts[deviceId]?.[stageId]?.active || 0;
            waitingCount = deviceUnitsCounts[deviceId]?.[stageId]?.waiting || 0;
        }

        const hasActiveJobs = activeCount > 0;
        const hasWaitingJobs = waitingCount > 0;
        const hasJobs = hasActiveJobs || hasWaitingJobs;

        return { activeCount, waitingCount, hasActiveJobs, hasWaitingJobs, hasJobs };
    }

    function createDeviceCard(device) {
        const state = getDeviceState(device);
        const deviceTypeName = deviceTypes[device.type] || 'Unknown';

        // Apply exact same state logic as operations dashboard
        let cardClass = 'device-card';

        if (!state.hasJobs) {
            // No jobs at all - inactive, grayscale, not clickable
            cardClass += ' inactive';
        } else if (!state.hasActiveJobs && state.hasWaitingJobs) {
            // Only waiting jobs - clickable but grayscale
            cardClass += ' waiting-only';
        } else {
            // Has active jobs (may also have waiting) - fully clickable and colored
            cardClass += ' clickable';
        }

        cardClass += ` size-${currentConfig.imageSize}`;

        let imageClass = 'device-image';
        // No need for specific image grayscale - entire card will be grayscale for waiting devices

        const badgeClass = `device-badges`;

        const deviceImg = device.img || 'devicesImages/no_device_img.PNG';
        const baseUrl = '{{ asset("") }}';

        return `
            <div class="${cardClass}"
                 data-device-id="${device.id}"
                 data-device-type="${device.type}"
                 data-device-name="${device.name}"
                 onclick="${state.hasJobs ? `handleDeviceClick(this, '${device.id}', '${device.type}')` : 'showNoJobsMessage()'}">

                ${currentConfig.showCategoryNames ? `<div class="device-type">${deviceTypeName}</div>` : ''}

                <div class="device-image-wrapper">
                    ${state.hasJobs ? `
                        <div class="${badgeClass}">
                            <div class="device-badge device-badge-blue">
                                ${state.activeCount}
                            </div>
                            ${device.type != 4 ? `
                                <div class="device-badge device-badge-red">
                                    ${state.waitingCount}
                                </div>
                            ` : ''}
                        </div>
                    ` : ''}

                    <img class="${imageClass}"
                         alt="${device.name}"
                         src="${baseUrl}${deviceImg}"
                         onerror="this.onerror=null; this.src='${baseUrl}devicesImages/no_device_img.PNG';" />
                </div>

                ${currentConfig.showDeviceNames ? `<div class="device-name">${device.name}</div>` : ''}
            </div>
        `;
    }

    function renderDevices() {
        console.log('🎨 renderDevices called');
        const container = document.getElementById('devicesGrid');
        console.log('🎨 container:', container);

        if (currentConfig.groupByCategory) {
            container.classList.add('grouped');

            const groupedDevices = {};
            devicesData.forEach(device => {
                const type = device.type;
                if (!groupedDevices[type]) {
                    groupedDevices[type] = [];
                }
                groupedDevices[type].push(device);
            });

            let html = '';
            Object.keys(groupedDevices).forEach(type => {
                const typeName = deviceTypes[type];
                const devices = groupedDevices[type];
                const categoryClass = `category-${stageTypes[type]}`;

                html += `
                    <div class="device-category-group">
                        <div class="category-header">
                            <div class="category-icon ${categoryClass}">
                                ${getStageIcon(type)}
                            </div>
                            <h3 class="category-title">${typeName}</h3>
                        </div>
                        <div class="category-devices">
                            ${devices.map(device => createDeviceCard(device)).join('')}
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        } else {
            console.log('🎨 Rendering devices in grid mode');
            container.classList.remove('grouped');
            const deviceCards = devicesData.map(device => createDeviceCard(device)).join('');
            container.innerHTML = deviceCards;

            console.log('🎨 Generated device cards for', devicesData.length, 'devices');
            console.log('🎨 Container after innerHTML:', container);

            // Ensure grid layout is applied after rendering
            setTimeout(() => {
                console.log('🎨 Applying grid layout after renderDevices...');
                applyGridLayout();
            }, 10);
        }
    }

    function applyGridLayout() {
        console.log('🔧 applyGridLayout called');
        const container = document.getElementById('devicesGrid');
        console.log('🔧 devicesGrid element:', container);
        console.log('🔧 groupByCategory:', currentConfig.groupByCategory);

        if (container) {
            console.log('🔧 Container found, current styles:', {
                display: container.style.display,
                gridTemplateColumns: container.style.gridTemplateColumns,
                gap: container.style.gap
            });

            if (!currentConfig.groupByCategory) {
                // Let CSS handle the grid layout, only apply size class
                container.className = `size-${currentConfig.imageSize}`;

                console.log('✅ Applied size class, CSS handles grid:', {
                    className: container.className,
                    computedDisplay: window.getComputedStyle(container).display,
                    computedColumns: window.getComputedStyle(container).gridTemplateColumns,
                    computedGap: window.getComputedStyle(container).gap
                });
            } else {
                console.log('⚠️ Skipping grid layout - groupByCategory is enabled');
            }
        } else {
            console.error('❌ devicesContent container not found!');
        }
    }

    function getStageIcon(stageId) {
        const icons = {
            '2': '⚙️', // Milling
            '3': '🖨️', // 3D Printing
            '4': '🔥', // Sintering
            '5': '🔨'  // Pressing
        };
        return icons[stageId] || '⚡';
    }

    // Device click handler - reuse existing dialog logic
    function handleDeviceClick(element, deviceId, stageId) {
        console.log(`Device clicked: ID=${deviceId}, Stage=${stageId}`);

        // Add visual feedback
        element.classList.add('clicked');
        setTimeout(() => {
            element.classList.remove('clicked');
        }, 200);

        // Add loading state
        element.classList.add('loading');

        const stageType = stageTypes[stageId] || 'unknown';

        // Remove loading state after a shorter delay to improve responsiveness
        setTimeout(() => {
            element.classList.remove('loading');
        }, 500);

        // Call existing handleClick function from operations dashboard with retry
        const tryOpenDialog = () => {
            if (typeof handleClick === 'function') {
                console.log(`Calling handleClick with device: ${deviceId}, type: ${stageType}`);
                handleClick(element, deviceId, stageType);
                return true;
            } else if (typeof window.handleClick === 'function') {
                console.log(`Calling window.handleClick with device: ${deviceId}, type: ${stageType}`);
                window.handleClick(element, deviceId, stageType);
                return true;
            }
            return false;
        };

        // Try immediately, then retry after a delay if operations JS is still loading
        if (!tryOpenDialog()) {
            setTimeout(() => {
                if (!tryOpenDialog()) {
                    console.warn('Dialog functions not available - opening fallback');
                    element.classList.remove('loading');

                    // Show a more user-friendly message
                    if (confirm(`Open active jobs for ${element.dataset.deviceName || 'Device ' + deviceId}?`)) {
                        // Try to manually trigger the appropriate dialog
                        const modal = document.querySelector(`[data-device-id="${deviceId}"]`);
                        if (modal && modal.click) {
                            modal.click();
                        } else {
                            alert('Device functionality is still loading. Please wait a moment and try again.');
                        }
                    }
                }
            }, 1000);
        }
    }

    function showNoJobsMessage() {
        // Show a more user-friendly message
        if (typeof showToast === 'function') {
            showToast('This device has no active or waiting jobs.', 'info');
        } else {
            alert('This device has no active or waiting jobs.');
        }
    }

    // Sortable functionality
    let sortableInstance = null;
    let sortableMode = false;
    function toggleSortableMode() {
        console.log('🎯 toggleSortableMode called');
        sortableMode = document.getElementById('sortableMode').checked;
        console.log('🎯 sortableMode:', sortableMode);

        const container = document.querySelector('.devices-page-container');
        console.log('🎯 container:', container);

        // Target the correct container based on grouping mode
        let devicesGrid;
        if (currentConfig.groupByCategory) {
            // For grouped mode, we can't easily sort, so disable it
            alert('Please disable "Group by Category" to enable sorting mode.');
            document.getElementById('sortableMode').checked = false;
            return;
        } else {
            devicesGrid = document.getElementById('devicesGrid');
            console.log('🎯 devicesGrid element:', devicesGrid);
        }

        if (sortableMode && devicesGrid) {
            console.log('Enabling sortable mode...');
            console.log('devicesGrid:', devicesGrid);
            console.log('Sortable available:', typeof Sortable !== 'undefined');

            container.classList.add('sortable-mode');

            // Get all device cards
            const deviceCards = devicesGrid.querySelectorAll('.device-card');
            console.log('Found device cards:', deviceCards.length);

            if (typeof Sortable === 'undefined') {
                alert('SortableJS library not loaded. Please refresh the page.');
                document.getElementById('sortableMode').checked = false;
                return;
            }

            // Initialize Sortable with minimal config first
            try {
                sortableInstance = Sortable.create(devicesGrid, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onStart: function(evt) {
                        console.log('🟢 Drag started on:', evt.item);
                    },
                    onMove: function(evt) {
                        console.log('🔄 Moving item');
                        return true; // Allow move
                    },
                    onEnd: function(evt) {
                        console.log('🟡 Drag ended, old index:', evt.oldIndex, 'new index:', evt.newIndex);

                        // Get the new order of device IDs
                        const deviceIds = Array.from(devicesGrid.children).map(card =>
                            card.dataset.deviceId
                        );

                        console.log('New device order:', deviceIds);

                        if (evt.oldIndex !== evt.newIndex) {
                            // Send new order to server
                            updateDeviceOrder(deviceIds);
                        }
                    }
                });

                console.log('✅ Sortable instance created successfully:', sortableInstance);
            } catch (error) {
                console.error('❌ Failed to create sortable instance:', error);
                alert('Failed to enable sorting mode: ' + error.message);
                document.getElementById('sortableMode').checked = false;
            }
        } else {
            container.classList.remove('sortable-mode');

            // Re-enable device click handlers
            const deviceCards = document.querySelectorAll('.device-card');
            deviceCards.forEach(card => {
                card.style.pointerEvents = 'auto';
                card.removeAttribute('draggable');
            });

            // Destroy Sortable instance
            if (sortableInstance) {
                sortableInstance.destroy();
                sortableInstance = null;
                console.log('Sortable instance destroyed');
            }
        }
    }

    function updateDeviceOrder(deviceIds) {
        fetch('/devices/reorder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ device_ids: deviceIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Device order updated successfully');
                // Show success message
                if (typeof showToast === 'function') {
                    showToast('Device order updated successfully', 'success');
                }
            } else {
                console.error('Failed to update device order:', data.error);
                // Reload page on error to restore original order
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error updating device order:', error);
            // Reload page on error to restore original order
            location.reload();
        });
    }

    // Close config panel when clicking outside
    document.addEventListener('click', function(e) {
        const panel = document.getElementById('configPanel');
        const toggle = document.querySelector('.config-toggle');

        if (!panel.contains(e.target) && !toggle.contains(e.target)) {
            panel.classList.remove('active');
        }
    });

    // ESC key handler for dialogs - ONLY for devices page
    function handleDevicePageEscapeKey(event) {
        if (event.key === 'Escape') {
            // Only handle escape if we're on the devices page
            if (!document.querySelector('.devices-page-container')) {
                return; // Not on devices page, don't interfere
            }

            // Find any open dialogs and close them
            const openDialogs = document.querySelectorAll('.sigma-workflow-modal.active, .modal.show');
            if (openDialogs.length > 0) {
                openDialogs.forEach(dialog => {
                    // Extract device ID from dialog ID if it's a device dialog
                    const dialogId = dialog.id;
                    if (dialogId && dialogId.includes('casesListDialog')) {
                        const deviceId = dialogId.replace('casesListDialog', '');
                        closeDeviceDialog(deviceId);
                    } else {
                        // Close modal using Bootstrap modal method if available
                        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                            const modalInstance = bootstrap.Modal.getInstance(dialog);
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                        } else if (typeof $ !== 'undefined' && $.fn.modal) {
                            $(dialog).modal('hide');
                        } else {
                            // Fallback - hide modal manually
                            dialog.style.display = 'none';
                            dialog.classList.remove('show', 'active');
                        }
                    }
                    console.log(`Devices page: Closed dialog via ESC key: ${dialogId}`);
                });
                return; // Don't close config panel if dialog was open
            }

            // Close configuration panel if open (only if no dialogs were open)
            const configPanel = document.getElementById('configPanel');
            if (configPanel && configPanel.classList.contains('active')) {
                configPanel.classList.remove('active');
                console.log('Devices page: Closed config panel via ESC key');
            }
        }
    }

    // Add escape key listener only for this page
    document.addEventListener('keydown', handleDevicePageEscapeKey);

    // Initialize devices page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Devices page loaded with', devicesData.length, 'devices');
        console.log('🚀 Current config:', currentConfig);

        // Load configuration and render devices
        loadConfig();
        updateBadgePositions();
        updateFontSizes();
        renderDevices();

        // Ensure grid layout is applied after initial render
        setTimeout(() => {
            console.log('🚀 Applying grid layout after page load...');
            applyGridLayout();
        }, 50);

        // Initialize any existing modal functionality
        setTimeout(() => {
            if (typeof initializeModals === 'function') {
                initializeModals();
            }
        }, 500);

        // Check if SortableJS loaded
        if (typeof Sortable === 'undefined') {
            console.error('SortableJS library failed to load');
        } else {
            console.log('SortableJS library loaded successfully');
        }

        // Debug viewport and grid capacity
        setTimeout(() => {
            const container = document.getElementById('devicesGrid');
            const grid = document.getElementById('devicesGrid');
            const pageContainer = document.querySelector('.devices-page-container');

            const viewport = window.innerWidth;
            const containerWidth = container ? container.offsetWidth : 0;
            const gridWidth = grid ? grid.offsetWidth : 0;
            const pageWidth = pageContainer ? pageContainer.offsetWidth : 0;

            console.log('🔍 Viewport width:', viewport);
            console.log('🔍 Page container width:', pageWidth);
            console.log('🔍 Grid width:', gridWidth);
            console.log('🔍 DevicesContent width:', containerWidth);
            console.log('🔍 Device size setting:', currentConfig.imageSize);

            const deviceSize = currentConfig.imageSize === 'small' ? 140 :
                              currentConfig.imageSize === 'large' ? 220 : 180;
            const gap = 25;
            const possibleColumns = Math.floor((containerWidth + gap) / (deviceSize + gap));
            console.log('🔍 Calculated columns that should fit:', possibleColumns);

            // Check CSS styles on elements
            if (container) {
                const containerStyles = window.getComputedStyle(container);
                console.log('🔍 Container computed width:', containerStyles.width);
                console.log('🔍 Container computed max-width:', containerStyles.maxWidth);
                console.log('🔍 Container computed min-width:', containerStyles.minWidth);
            }
        }, 100);
    });

    // Add global CSS for modal support
    if (typeof addDevicePageStyles === 'undefined') {
        window.addDevicePageStyles = true;
        const style = document.createElement('style');
        style.textContent = `
            .devices-page-container .sigma-workflow-modal {
                z-index: 1050;
                cursor: pointer; /* Show that backdrop is clickable */
            }

            .device-card:focus {
                outline: 2px solid #007bff;
                outline-offset: 2px;
            }

            .device-card.inactive:focus {
                outline: 2px solid #6c757d;
            }

            /* Modal backdrop click handling */
            .devices-page-container .sigma-workflow-dialog {
                cursor: default; /* Reset cursor for dialog content */
                pointer-events: auto;
            }

            /* Ensure dialog content doesn't propagate clicks to backdrop */
            .devices-page-container .sigma-workflow-modal .sigma-workflow-dialog > * {
                pointer-events: auto;
            }

            /* Fade animations for closing dialogs */
            .devices-page-container .sigma-workflow-dialog.fade-out {
                animation: fadeOutDialog 0.3s ease-in forwards;
            }

            @keyframes fadeOutDialog {
                from {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
                to {
                    opacity: 0;
                    transform: translateY(-20px) scale(0.95);
                }
            }
        `;
        document.head.appendChild(style);
    }
</script>
@endpush
