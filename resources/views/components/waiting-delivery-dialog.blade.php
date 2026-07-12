@props(['title', 'btnText', 'type', 'drivers', 'stageId'])

<style>
    #DeliveryDialog .sigma-workflow-dialog {
        width: calc(100% - 32px) !important;
        max-width: 720px;
        transform-origin: center;
    }

    #DeliveryDialog {
        align-items: center;
        justify-content: center;
    }

    #DeliveryDialog .sigma-workflow-dialog.fade-in {
        animation: deliveryDialogFadeIn 180ms ease-out both !important;
    }

    #DeliveryDialog .sigma-workflow-dialog.fade-out {
        animation: deliveryDialogFadeOut 140ms ease-in both !important;
    }

    @keyframes deliveryDialogFadeIn {
        from {
            opacity: 0;
            transform: scale(0.96);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes deliveryDialogFadeOut {
        from {
            opacity: 1;
            transform: scale(1);
        }
        to {
            opacity: 0;
            transform: scale(0.98);
        }
    }

    #DeliveryDialog .sigma-workflow-header {
        background: #fff;
        background-image: none;
        padding: 18px 24px;
    }

    #DeliveryDialog .sigma-workflow-title,
    #DeliveryDialog .sigma-button,
    #DeliveryDialog .sigma-driver-name {
        font-family: 'Inter', sans-serif;
    }

    #DeliveryDialog .sigma-workflow-title {
        font-size: 18px;
        font-weight: 600;
        text-shadow: none;
    }

    #DeliveryDialog .sigma-workflow-body {
        padding: 32px 24px;
    }

    #DeliveryDialog .sigma-drivers-grid {
        gap: 24px;
        padding: 0;
    }

    #DeliveryDialog .sigma-driver-card {
        width: 160px;
        padding: 8px;
    }

    #DeliveryDialog .sigma-driver-image-container {
        width: 132px;
        height: 132px;
        overflow: hidden;
        border-radius: 50%;
        background: #f1f3f5;
    }

    #DeliveryDialog .sigma-driver-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #DeliveryDialog .sigma-driver-name {
        margin-top: 8px;
        font-size: 14px;
    }

    #DeliveryDialog .sigma-workflow-footer {
        justify-content: flex-end;
        padding: 14px 20px;
    }

    #DeliveryDialog .sigma-button {
        width: auto !important;
        min-width: 100px;
        min-height: 36px;
        padding: 7px 12px !important;
        color: #fff;
        font-size: 14px !important;
        font-weight: 400;
        line-height: 20px;
    }

    @media (max-width: 480px) {
        #DeliveryDialog .sigma-workflow-header {
            padding: 15px 20px !important;
        }

        #DeliveryDialog .sigma-workflow-body {
            padding: 24px 12px !important;
        }

        #DeliveryDialog .sigma-drivers-grid {
            gap: 12px;
        }

        #DeliveryDialog .sigma-driver-card {
            width: 132px;
        }

        #DeliveryDialog .sigma-driver-image-container {
            width: 112px;
            height: 112px;
        }
    }
</style>

<div class="sigma-workflow-modal waiting-dialog sigma-modal--waiting-delivery" id="DeliveryDialog" tabindex="-1" role="dialog"
     onclick="dismissDeliveryDialog(event)">
    <div class="sigma-workflow-dialog">
        <!-- Header with close button -->
        <div class="sigma-workflow-header">
            <span class="sigma-workflow-title">{{ $title }}</span>
            <x-sigma-close-button onclick="closeModal({id: 'DeliveryDialog', isWaiting:false})" />
        </div>

        <!-- Driver selection grid -->
        <div class="sigma-workflow-body">
            <div class="sigma-drivers-grid">

                <!-- Show all delivery drivers -->
                @foreach($drivers as $driver)
                    <div class="sigma-driver-card"
                         onclick="selectDeliveryDriver(this, {{ $driver->id }})">
                        <div class="sigma-driver-image-container">
                            <img src="{{ asset($driver->avatar_path) }}"
                                 alt="{{ $driver->first_name }} {{ $driver->last_name }}"
                                 class="sigma-driver-image grayscale">
                        </div>
                        <div class="sigma-driver-name">{{ $driver->name_initials ?? $driver->first_name }}</div>
                    </div>
                @endforeach
            </div>


        </div>

        <!-- Action button -->
        <div class="sigma-workflow-footer">
            <button type="button"
                    class="sigma-button "
                    id="action-button-delivery"
                    style="background-color: var(--main-orange)"
                    disabled
                    onclick="submitDeliveryAssignment()">
                {{ $btnText }}
            </button>
        </div>
    </div>
</div>

<form id="delivery-form" method="POST" action="{{ route('assign-multiple-deliveries') }}" class="d-none">
    @csrf
    <input type="hidden" name="deviceId-delivery" id="driver-id-input" value="">
    <input type="hidden" name="WaitingPopupCheckBoxesdelivery" id="case-ids-input" value="">
</form>

<script>
// Helper function to close the modal properly
function closeModal(options) {
    const {id, isWaiting = false} = options;
    const modalId = id + (isWaiting ? "-waiting" : "");
    const modal = document.getElementById(modalId);

    if (!modal) {
        console.error(`Modal not found: ${modalId}`);
        return;
    }

    console.log(`Closing delivery modal: ${modalId}`);

    // Remove focus if modal contains active element
    if (modal.contains(document.activeElement)) {
        document.activeElement.blur();
    }

    // Clear any pending animations
    const dialogContent = modal.querySelector('.sigma-workflow-dialog') || modal.querySelector('.modal-content');
    if (dialogContent) {
        dialogContent.classList.remove('fade-in');
        dialogContent.classList.add('fade-out');
    }

    // Reset delivery dialog state
    if (modalId === 'DeliveryDialog') {
        // Reset driver selection
        document.querySelectorAll('.sigma-driver-card').forEach(card => {
            card.classList.remove('selected');
            const img = card.querySelector('.sigma-driver-image');
            if (img) {
                img.classList.add('grayscale');
            }
        });

        // Reset the assign button state
        const assignButton = document.getElementById('action-button-delivery');
        if (assignButton) {
            assignButton.disabled = true;
            assignButton.classList.remove('btn-loading', 'disabled');
            assignButton.innerText = 'Assign';
        }

        // Clear selected driver
        window.selectedDriverId = null;

        // Reset form inputs
        const driverInput = document.getElementById('driver-id-input');
        const caseIdsInput = document.getElementById('case-ids-input');
        if (driverInput) driverInput.value = '';
        if (caseIdsInput) caseIdsInput.value = '';
    }

    // Hide dialog after animation completes
    setTimeout(() => {
        modal.classList.remove('active');
        if (dialogContent) {
            dialogContent.classList.remove('fade-out', 'fade-in');
        }

        // Clean up any overlays
        document.querySelectorAll('.modal-backdrop, .modal-overlay').forEach(backdrop => {
            backdrop.remove();
        });

        if (typeof window.updateDialogScrollLock === 'function') {
            window.updateDialogScrollLock();
        }
    }, 300);
}

function dismissDeliveryDialog(event) {
    if (event.target === event.currentTarget) {
        closeModal({id: 'DeliveryDialog', isWaiting: false});
    }
}
</script>
