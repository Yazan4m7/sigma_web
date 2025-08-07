<?php
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
?>

<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Include existing stylesheets from operations dashboard -->
    <link href="<?php echo e(asset('assets')); ?>/css/ysh-custom-css/dialog.css" rel="stylesheet"/>
    <link href="<?php echo e(asset('assets')); ?>/css/ysh-custom-css/OperationsDashboardStyling.css" rel="stylesheet"/>
    <link href="<?php echo e(asset('assets')); ?>/css/active-cases.css" rel="stylesheet"/>
    <link href="<?php echo e(asset('assets')); ?>/css/waiting-dialog.css" rel="stylesheet"/>
    <link href="<?php echo e(asset('assets')); ?>/css/v3styles.css" rel="stylesheet">
    <link href="<?php echo e(asset('assets')); ?>/css/devices-page.css" rel="stylesheet">

    <style>
        /* Main devices page container */
        .devices-page-container {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .devices-page-header {
            text-align: center;
            margin-bottom: 30px;
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
            margin-bottom: 0;
        }

        /* Device grid layout */
        .devices-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 25px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 0;
        }

        /* Individual device card */
        .device-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            min-height: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        .device-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .device-card.inactive {
            cursor: not-allowed;
            opacity: 0.7;
        }

        .device-card.inactive:hover {
            transform: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Device image */
        .device-image {
            width: <?php echo e($deviceConfig["width"] ?? '100%'); ?>;
            height: <?php echo e($deviceConfig["height"] ?? 'auto'); ?>;
            max-width: <?php echo e($deviceConfig["max_width"] ?? '150px'); ?>;
            max-height: 120px;
            object-fit: contain;
            margin-bottom: 15px;
            border-radius: <?php echo e($deviceConfig["border_radius"] ?? '8px'); ?>;
            background: <?php echo e($deviceConfig["background"] ?? 'transparent'); ?>;
        }

        .device-image.grayscale {
            filter: grayscale(100%);
        }

        /* Device name (initially hidden as requested) */
        .device-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-top: 10px;
            display: none; /* Hidden as requested but kept in DOM */
        }

        /* Badge container */
        .device-badges {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        /* Individual badges */
        .device-badge {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .device-badge-blue {
            background-color: #007bff;
        }

        .device-badge-red {
            background-color: #dc3545;
        }

        /* Device type indicator */
        .device-type {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .devices-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 20px;
                padding: 15px;
            }
            
            .device-card {
                padding: 15px;
                min-height: 220px;
            }
            
            .device-image {
                max-width: 120px;
            }
            
            .devices-page-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .devices-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 15px;
                padding: 10px;
            }
            
            .device-card {
                padding: 12px;
                min-height: 200px;
            }
            
            .device-image {
                max-width: 100px;
            }
            
            .devices-page-title {
                font-size: 1.8rem;
            }
        }

        /* No devices message */
        .no-devices {
            grid-column: 1 / -1;
            text-align: center;
            color: #6c757d;
            font-size: 1.2rem;
            padding: 40px;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="devices-page-container">
    <div class="devices-page-header">
        <h1 class="devices-page-title">All Devices</h1>
        <p class="devices-page-subtitle">Manufacturing Equipment Overview</p>
    </div>

    <div class="devices-grid">
        <?php if(isset($devices) && $devices->count() > 0): ?>
            <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $deviceId = $device->id;
                    $stageId = $device->type;
                    
                    // Get badge counts based on device type
                    if ($stageId == 3) { // 3D Printing
                        $activeCount = isset($deviceCounts[$deviceId]['activeBuilds']) ? $deviceCounts[$deviceId]['activeBuilds'] : 0;
                        $waitingCount = isset($deviceCounts[$deviceId]['waitingBuilds']) ? $deviceCounts[$deviceId]['waitingBuilds'] : 0;
                    } else {
                        $activeCount = isset($deviceCounts[$deviceId][$stageId]['active']) ? $deviceCounts[$deviceId][$stageId]['active'] : 0;
                        $waitingCount = isset($deviceCounts[$deviceId][$stageId]['waiting']) ? $deviceCounts[$deviceId][$stageId]['waiting'] : 0;
                    }
                    
                    $hasActiveJobs = $activeCount > 0;
                    $hasWaitingJobs = $waitingCount > 0;
                    $hasJobs = $hasActiveJobs || $hasWaitingJobs;
                    
                    // Device type names (only for stages that use devices)
                    $deviceTypes = [
                        2 => 'Milling', 
                        3 => '3D Printing',
                        4 => 'Sintering',
                        5 => 'Pressing'
                    ];
                    
                    $deviceTypeName = $deviceTypes[$stageId] ?? 'Unknown';
                ?>

                <div class="device-card <?php echo e($hasActiveJobs ? 'clickable' : ($hasWaitingJobs ? 'waiting' : 'inactive')); ?>"
                     data-device-id="<?php echo e($deviceId); ?>" 
                     data-device-type="<?php echo e($stageId); ?>"
                     data-device-name="<?php echo e($device->name); ?>"
                     onclick="<?php echo e($hasJobs ? "handleDeviceClick(this, '{$deviceId}', '{$stageId}')" : 'showNoJobsMessage()'); ?>">
                    
                    <!-- Device Type -->
                    <div class="device-type"><?php echo e($deviceTypeName); ?></div>
                    
                    <!-- Badge Container -->
                    <?php if($hasJobs): ?>
                        <div class="device-badges">
                            <div class="device-badge device-badge-blue" title="<?php echo e($activeCount); ?> active jobs">
                                <?php echo e($activeCount); ?>

                            </div>
                            <?php if($stageId != 4): ?> 
                                <div class="device-badge device-badge-red" title="<?php echo e($waitingCount); ?> waiting jobs">
                                    <?php echo e($waitingCount); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Device Image -->
                    <img class="device-image <?php echo e(!$hasActiveJobs && $hasWaitingJobs ? 'grayscale' : ''); ?>" 
                         alt="<?php echo e($device->name); ?>"
                         src="<?php echo e(asset(isset($device->img) ? $device->img : 'devicesImages/no_device_img.PNG')); ?>" 
                         onerror="this.onerror=null; this.src='<?php echo e(asset('devicesImages/no_device_img.PNG')); ?>';" />
                    
                    <!-- Device Name (hidden but in DOM) -->
                    <div class="device-name"><?php echo e($device->name); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="no-devices">No devices available.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Include waiting dialog components for device-using stages only -->
<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.waiting-dialog','data' => ['title' => 'Select Milling Machine','btnText' => 'NEST','type' => 'milling','devices' => $devices,'stageId' => '2','showBuildName' => true]]); ?>
<?php $component->withName('waiting-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Select Milling Machine','btnText' => 'NEST','type' => 'milling','devices' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($devices),'stageId' => '2','showBuildName' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.waiting-dialog','data' => ['title' => 'Select 3D Printer','btnText' => 'SET','type' => '3dprinting','devices' => $devices,'stageId' => '3','showBuildName' => true]]); ?>
<?php $component->withName('waiting-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Select 3D Printer','btnText' => 'SET','type' => '3dprinting','devices' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($devices),'stageId' => '3','showBuildName' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.waiting-dialog','data' => ['title' => 'Select Sintering Furnace','btnText' => 'START','type' => 'sintering','devices' => $devices,'stageId' => '4','showBuildName' => false]]); ?>
<?php $component->withName('waiting-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Select Sintering Furnace','btnText' => 'START','type' => 'sintering','devices' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($devices),'stageId' => '4','showBuildName' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.waiting-dialog','data' => ['title' => 'Select Pressing Furnace','btnText' => 'SET','type' => 'pressing','devices' => $devices,'stageId' => '5','showBuildName' => true]]); ?>
<?php $component->withName('waiting-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Select Pressing Furnace','btnText' => 'SET','type' => 'pressing','devices' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($devices),'stageId' => '5','showBuildName' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.waiting-3dprinting-dialog','data' => ['title' => '3D Printing Setup','devices' => $devices,'stageId' => '3']]); ?>
<?php $component->withName('waiting-3dprinting-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => '3D Printing Setup','devices' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($devices),'stageId' => '3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

<!-- Generate device-specific dialogs for each device -->
<?php if(isset($devices)): ?>
    <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $deviceType = $device->type;
            $deviceId = $device->id;
        ?>
        
        <?php switch($deviceType):
            case (2): ?> 
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.active-cases-dialog','data' => ['title' => 'Milling Jobs','btnText' => 'COMPLETE','type' => 'milling','deviceId' => $deviceId,'isBuilds' => false]]); ?>
<?php $component->withName('active-cases-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Milling Jobs','btnText' => 'COMPLETE','type' => 'milling','deviceId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($deviceId),'isBuilds' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                <?php break; ?>
            <?php case (3): ?> 
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.active-cases-dialog','data' => ['title' => 'Printer Builds','btnText' => 'COMPLETE','type' => '3dprinting','deviceId' => $deviceId,'isBuilds' => true]]); ?>
<?php $component->withName('active-cases-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Printer Builds','btnText' => 'COMPLETE','type' => '3dprinting','deviceId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($deviceId),'isBuilds' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                <?php break; ?>
            <?php case (4): ?> 
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.active-cases-dialog','data' => ['title' => 'Sintering Jobs','btnText' => 'COMPLETE','type' => 'sintering','deviceId' => $deviceId,'isBuilds' => false]]); ?>
<?php $component->withName('active-cases-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Sintering Jobs','btnText' => 'COMPLETE','type' => 'sintering','deviceId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($deviceId),'isBuilds' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                <?php break; ?>
            <?php case (5): ?> 
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.active-cases-dialog','data' => ['title' => 'Pressing Jobs','btnText' => 'COMPLETE','type' => 'pressing','deviceId' => $deviceId,'isBuilds' => false]]); ?>
<?php $component->withName('active-cases-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Pressing Jobs','btnText' => 'COMPLETE','type' => 'pressing','deviceId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($deviceId),'isBuilds' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                <?php break; ?>
        <?php endswitch; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<!-- Include required scripts from operations dashboard -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://js.cookie.js/dist/js.cookie.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/5.0.4/js/dataTables.fixedColumns.js"></script>
<script src="<?php echo e(asset('assets')); ?>/js/ysh-custom-js/operationsDashboardJS.js"></script>
<script src="<?php echo e(asset('assets')); ?>/js/v3scripts.js"></script>

<script>
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
        
        // Get device type string for existing handlers (only device-using stages)
        const stageTypes = {
            '2': 'milling', 
            '3': '3dprinting',
            '4': 'sintering',
            '5': 'pressing'
        };
        
        const stageType = stageTypes[stageId] || 'unknown';
        
        // Remove loading state after a delay
        setTimeout(() => {
            element.classList.remove('loading');
        }, 1000);
        
        // Call existing handleClick function from operations dashboard
        if (typeof handleClick === 'function') {
            console.log(`Calling handleClick with device: ${deviceId}, type: ${stageType}`);
            handleClick(element, deviceId, stageType);
        } else {
            console.error('handleClick function not found, trying openDeviceDialog');
            // Try alternative function
            if (typeof openDeviceDialog === 'function') {
                openDeviceDialog(deviceId, stageType);
            } else {
                // Fallback behavior
                console.error('No dialog functions found');
                element.classList.remove('loading');
                alert(`Device ${deviceId} clicked (${stageType}) - Dialog system not available`);
            }
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
    
    // Initialize devices page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Devices page loaded with', document.querySelectorAll('.device-card').length, 'devices');
        
        // Initialize any existing modal functionality
        if (typeof initializeModals === 'function') {
            initializeModals();
        }
        
        // Add click event listeners as backup
        const deviceCards = document.querySelectorAll('.device-card');
        deviceCards.forEach(card => {
            if (!card.classList.contains('inactive')) {
                const deviceId = card.getAttribute('data-device-id');
                const deviceType = card.getAttribute('data-device-type');
                
                // Add double-click as alternative activation
                card.addEventListener('dblclick', function() {
                    console.log('Double-click on device:', deviceId);
                    handleDeviceClick(this, deviceId, deviceType);
                });
            }
        });
        
        // Add keyboard navigation support
        deviceCards.forEach((card, index) => {
            card.setAttribute('tabindex', card.classList.contains('inactive') ? '-1' : '0');
            
            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    if (!this.classList.contains('inactive')) {
                        this.click();
                    }
                }
            });
        });
    });
    
    // Add global CSS for modal support
    if (typeof addDevicePageStyles === 'undefined') {
        window.addDevicePageStyles = true;
        const style = document.createElement('style');
        style.textContent = `
            .devices-page-container .sigma-workflow-modal {
                z-index: 1050;
            }
            
            .device-card:focus {
                outline: 2px solid #007bff;
                outline-offset: 2px;
            }
            
            .device-card.inactive:focus {
                outline: 2px solid #6c757d;
            }
        `;
        document.head.appendChild(style);
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', ['pageSlug' => 'devices'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/devices/devices-page.blade.php ENDPATH**/ ?>