@props(['title', 'btnText', 'type', 'drivers', 'stageId'])

<div class="sigma-workflow-modal waiting-dialog" id="DeliveryDialog" tabindex="-1" role="dialog">
    <div class="sigma-workflow-dialog">
        <!-- Header with close button -->
        <div class="sigma-workflow-header">
            <h2 class="sigma-workflow-title">{{ $title }}</h2>
            <button class="sigma-close-button" onclick="closeModal({id: 'DeliveryDialog', isWaiting:false})">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- Driver selection grid -->
        <div class="sigma-workflow-body">
            <div class="sigma-drivers-grid">
                <!-- Add "ME" option -->
                <div class="sigma-driver-card"
                     onclick="selectDeliveryDriver(this, {{ auth()->user()->id }})">
                    <div class="sigma-driver-image-container">
                        <img src="{{ asset('/users/me_silhouette.png') }}"
                             alt="Me (Self Assign)"
                             class="sigma-driver-image grayscale">
                    </div>
                    <div class="sigma-driver-name">ME</div>
                </div>

                <!-- Show all delivery drivers -->
                @foreach($drivers as $driver)
                    <div class="sigma-driver-card"
                         onclick="selectDeliveryDriver(this, {{ $driver->id }})">
                        <div class="sigma-driver-image-container">
                            <img src="{{ $driver->has_photo ? asset('/users/'.$driver->id.'/profile_picture.png') : asset('/users/no_profile_picture.png') }}"
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

    // Add fade-out animation
    const dialogContent = modal.querySelector('.sigma-workflow-dialog') || modal.querySelector('.modal-content');
    if (dialogContent) {
        dialogContent.classList.add('fade-out');
    }

    // Hide dialog after animation completes
    setTimeout(() => {
        modal.classList.remove('active');
        modal.style.display = 'none';
        if (dialogContent) {
            dialogContent.classList.remove('fade-out');
        }
    }, 300);
}
</script>
