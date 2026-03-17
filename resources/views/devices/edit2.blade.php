@extends('layouts.app', ['pageSlug' => 'Edit ' . $device->name])
@push('css')
<style>
    .image-picker {
        max-width: 250%;
        max-height: 250%;
        box-shadow: 0 0 10px 0;
        position: absolute;
    }

    /* Modern horizontal device sorting UI */
    #devices-list-horizontal {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        min-height: 150px;
    }

    .device-card-sortable {
        background: white;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: move;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        position: relative;
        min-width: 140px;
        max-width: 160px;
    }

    .device-card-sortable:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        border-color: #007bff;
    }

    .device-card-sortable.sortable-ghost {
        opacity: 0.4;
        background: #e9ecef;
    }

    .device-card-sortable.sortable-drag {
        opacity: 0.8;
        transform: rotate(5deg);
    }

    .device-card-sortable img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        margin-bottom: 10px;
        border-radius: 6px;
    }

    .device-card-sortable .device-name {
        font-weight: 600;
        text-align: center;
        font-size: 0.9rem;
        color: #333;
        margin-bottom: 5px;
    }

    .device-card-sortable .drag-handle {
        position: absolute;
        top: 5px;
        right: 5px;
        color: #999;
        font-size: 1.2rem;
    }

    .device-card-sortable .order-number {
        position: absolute;
        top: 5px;
        left: 5px;
        background: #007bff;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .sort-section {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
        border: 1px solid #dee2e6;
    }

    .sort-section-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #007bff;
    }

    .sort-section-header i {
        color: #007bff;
        margin-right: 10px;
    }

    .sort-help-text {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 15px;
        padding: 10px;
        background: #f0f7ff;
        border-left: 3px solid #007bff;
        border-radius: 4px;
    }
</style>
@endpush
@section('content')
    <form method="POST" action="{{ route('edit-device', $device->id) }}" class="card" enctype="multipart/form-data" id="device-form">
        @csrf
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h6 class="kt-portlet__head-title">
                    <i class="fa fa-suitcase" style="width:3%"></i> Device Info:
                </h6>
            </div>
        </div>
        <input value="{{$device->id}}" type="hidden" name="device_id"/>
        <input type="hidden" name="device_order" id="device-order-input" value=""/>
        <hr style="margin-top: 0;">
        <div class="row">
            <div class="col-md-3 col-xs-6 col-l-3 col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Device name:</label></div>
                <div class="col-md-12 col-xs-12">
                    <input class="form-control" type="text" name="device_name" required placeholder="Device name" value="{{ $device->name }}" />
                    <span class="help-block text-muted"><small></small></span>
                </div>
            </div>
            <div class="col-md-3 col-xs-6 col-l-3 col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Device Type:</label></div>
                <div class="col-md-12 col-xs-12">
                    <select class="form-control selectpicker" id="dev" name="device_type">
                        <option value="3" {{ $device->type == 3 ? 'selected' : '' }}>3D Printer</option>
                        <option value="2" {{ $device->type == 2 ? 'selected' : '' }}>Milling Machine</option>
                        <option value="4" {{ $device->type == 4 ? 'selected' : '' }}>Sintering Furnace</option>
                        <option value="5" {{ $device->type == 5 ? 'selected' : '' }}>Pressing Furnace</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 col-xs-6 col-l-3 col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Device Image:</label></div>
                <label for="image-picker">
                    <img id="image-preview" class="image-picker" src="{{ asset($device->img) }}" alt="Machine/Device" />
                </label>
                <input id="image-picker" value="" type="file" accept="image/png" name="device_image" style="display:contents"/>
            </div>
        </div>

        <br/>

        <!-- Device Sorting Section - Moved BEFORE submit button -->
        <div class="sort-section">
            <div class="sort-section-header">
                <i class="fa fa-sort fa-2x"></i>
                <h6 class="mb-0">Sort Devices Display Order</h6>
            </div>
            <div class="sort-help-text">
                <i class="fa fa-info-circle"></i> Drag and drop to reorder devices. The order will be saved with the form submission.
            </div>
            <div id="devices-list-horizontal">
                @foreach($devices_of_same_type as $index => $d)
                    <div class="device-card-sortable" data-id="{{ $d->id }}">
                        <span class="order-number">{{ $index + 1 }}</span>
                        <span class="drag-handle"><i class="fa fa-grip-vertical"></i></span>
                        <img src="{{ asset($d->img) }}" alt="{{ $d->name }}">
                        <div class="device-name">{{ $d->name }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <br/>

        <div class="form-group mb-0">
            <div>
                <button type="submit" class="btn btn-info waves-effect waves-light">
                    <i class="fa fa-save"></i> Submit & Save Order
                </button>
                <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                    <i class="fa fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </form>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('form').parsley();

            // Initialize horizontal sortable list
            const el = document.getElementById('devices-list-horizontal');
            const sortable = Sortable.create(el, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: function (evt) {
                    // Update order numbers after sorting
                    updateOrderNumbers();
                    // Update hidden input with new order
                    updateDeviceOrder();
                }
            });

            // Function to update order numbers
            function updateOrderNumbers() {
                const cards = document.querySelectorAll('.device-card-sortable');
                cards.forEach((card, index) => {
                    const orderNum = card.querySelector('.order-number');
                    if (orderNum) {
                        orderNum.textContent = index + 1;
                    }
                });
            }

            // Function to update hidden input with device order
            function updateDeviceOrder() {
                const deviceIds = sortable.toArray();
                document.getElementById('device-order-input').value = JSON.stringify(deviceIds);
            }

            // Set initial order on page load
            updateDeviceOrder();

            // Update device order before form submission
            $('#device-form').on('submit', function(e) {
                updateDeviceOrder();
                // Form will submit normally
            });
        });

        $("#image-picker").change(function (event) {
            const file = this.files[0];
            const fileType = file.type;
            const fileSize = file.size;

            // Validate file type and size
            if (fileType !== 'image/png') {
                alert('Only PNG images are allowed.');
                this.value = ''; // Clear the input
                return;
            }
            if (fileSize > 4 * 1024 * 1024) { // 4MB in bytes
                alert('Image size must not exceed 4MB.');
                this.value = ''; // Clear the input
                return;
            }

            readURL(this);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#image-preview').attr('src', e.target.result);
                }
                $('#image-picker').css("display", "none");
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script type="text/javascript" src="{{ asset('assets/plugins/parsleyjs/dist/parsley.min.js') }}"></script>
@endpush
