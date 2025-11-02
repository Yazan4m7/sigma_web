<!-- waiting-3dprinting-dialog.blade.php -->
@props([
    'title',
    'devices',
    'stageId'
])

<div class="sigma-workflow-modal waiting-dialog animate__animated animate__bounc" id="3dprinting-waiting" tabindex="-1" role="dialog">
    <div class="sigma-workflow-dialog">
        <!-- Header with close button -->
        <div class="sigma-workflow-header">
            <h2 class="sigma-workflow-title waiting">{{ $title }}</h2>
            <button class="sigma-close-button" onclick="closeModal({id: '3dprinting', isWaiting:true})">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- Machine selection grid -->
        <div class="sigma-workflow-body">

            <div class="sigma-machines-grid">
                @if(isset($devices) && $devices->count() > 0)
                    @foreach($devices->where('type', $stageId) as $device)
                        <div class="sigma-machine-card 3dprinting"
                             onclick="selectMachine(this, '3dprinting', {{ $device['id'] }})">
                            <div class="sigma-machine-image-container">
                                <img src="{{ asset(isset($device['img']) ? $device['img'] : 'images/default-device.png') }}"
                                     alt="{{ isset($device['name']) ? $device['name'] : 'Device' }}"
                                     class="sigma-machine-image"
                                     onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;color:#999;font-size:12px;text-align:center;padding:10px;\'>Image Not Found</div>';">
                            </div>
                            <div class="sigma-machine-name">{{ isset($device['name']) ? $device['name'] : 'Unknown Device' }}</div>
                        </div>
                    @endforeach
                @else
                    <div class="no-devices-message">No devices available for this stage.</div>
                @endif
            </div>

            <!-- Build name and Material Type inputs -->
            <div class="sigma-inputs-container row" style="display: flex; gap: 10px; align-items: end;">

                <!-- Build name input -->
                <div class="sigma-form-group col-md-6 col-6" style="flex: 1;">

                    <input type="text"
                           id="sigma-build-name-3dprinting"
                           class="sigma-form-control {{$stageConfig['3dprinting']['multiple-waiting']?'multiple-choice' :'single-choice' }}"
                           placeholder="Enter Build name"
                           oninput="validateAndSetBuildName('3dprinting')">
                </div>

                <!-- Material Type selection for 3D printing -->
                <div class="sigma-form-group col-md-6 col-6" style="">



                    <select id="sigma-material-type-3dprinting"
                            class="sigma-form-control "
                            onchange="validateMaterialTypeSelection('3dprinting')">
                        <!-- Options will be populated by JavaScript based on selected cases -->
                    </select>

                </div>

            </div>


        </div>

        <!-- Action button -->
        <div class="sigma-workflow-footer">
            <button type="button"
                    class="sigma-button 3dprinting"
                    id="sigma-action-button-3dprinting" style = "background-color: var(--main-orange)"
                    disabled
                    onclick="submitWorkflow('3dprinting')">
                SET
            </button>

        </div>
    </div>
</div>

<form id="hidden-form-3dprinting" method="POST" action="/set-cases-on-printer" class="d-none">
    @csrf
    <input type="hidden" name="type" value="3dprinting">
    <input type="hidden" name="deviceId" id="device-id-3dprinting" value="">
    <input type="hidden" name="WaitingPopupCheckBoxes3dprinting[]" id="case-ids-3dprinting" value="">
    <input type="hidden" name="buildName" id="build-name-3dprinting" value="">
</form>


