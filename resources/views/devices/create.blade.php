@extends('layouts.app' ,[ 'pageSlug' => 'Create Device'])
@push('css')
<style>
    .image-picker {
        max-width: 250%;
        max-height: 250%;
        box-shadow: 0 0 10px 0;
        position: absolute;
    }
    .device-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    .device-card img {
        width: 50px;
        height: 50px;
        margin-right: 1rem;
    }
</style>
@endpush
@section('content')
    <form method="POST" action="{{route('new-device')}}" class="card" enctype="multipart/form-data">
        @csrf
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h6 class="kt-portlet__head-title">
                    <i class="fa  fa-suitcase" style="width:3%"></i> Device Info:
                </h6>
            </div>
        </div>
        <hr style="margin-top: 0;">
        <div class="row">

            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12 "><label >Device name:</label></div>
                <div class="col-md-12 col-xs-12">
                    <input class="form-control btn dropdown-toggle btn-light" type="text" name="device_name" required placeholder="Device name"/>
                    <span class="help-block text-muted"><small></small></span>
                </div>

            </div>
            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Device Type:</label></div>
                <div class="col-md-12 col-xs-12">
                    <select class="form-control selectpicker  border: 1px solid grey;" id="device_type" name="device_type" style="  border: 1px solid grey;">
                        <option value="3">3D Printer</option>
                        <option value="2">Milling Machine</option>
                        <option value="4">Sintering Furnace</option>
                        <option value="5">Pressing Furnace</option>
                    </select>
                </div>

            </div>




            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12" style="padding-bottom: 0"><label>Device Image:</label></div>
                <label for="image-picker">
                    <img id="image-preview" class="image-picker" alt="" src=""/>
                </label>
                <input id="image-picker" type="file" accept="image/*" name="device_image"/>
            </div>
        </div>
        <br/>
        <br/>
        <div class=" form-group ">
            <div class="form-group mb-0">
                <div style="">
                    <button type="submit" class="btn btn-info waves-effect waves-light">
                        Submit
                    </button>
                    <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                        Cancel
                    </button>
                </div>
            </div>

        </div>
    </form>

    <div class="card">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h6 class="kt-portlet__head-title">
                    <i class="fa fa-sort" style="width:3%"></i> Sort Devices
                </h6>
            </div>
        </div>
        <div class="card-body">
            <div id="devices-list" class="list-group"></div>
            <button class="btn btn-primary mt-3" id="save-order">Save Order</button>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            $('form').parsley();

            const deviceTypeDropdown = $('#device_type');
            const devicesList = $('#devices-list');
            let sortable = null;

            function fetchDevices(type){
                $.ajax({
                    url: `/devices/by-type/${type}`,
                    type: 'GET',
                    success: function(devices){
                        devicesList.empty();
                        devices.forEach(device => {
                            devicesList.append(`
                                <div class="device-card list-group-item" data-id="${device.id}">
                                    <img src="/{{ asset('') }}${device.img}" alt="${device.name}">
                                    <span>${device.name}</span>
                                </div>
                            `);
                        });

                        if(sortable){
                            sortable.destroy();
                        }

                        sortable = Sortable.create(devicesList[0], {
                            animation: 150,
                        });
                    }
                });
            }

            deviceTypeDropdown.on('change', function(){
                fetchDevices($(this).val());
            });

            fetchDevices(deviceTypeDropdown.val());

            $('#save-order').on('click', function(){
                const deviceIds = sortable.toArray();
                $.ajax({
                    url: '{{ route("devices-reorder") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        device_ids: deviceIds
                    },
                    success: function(response){
                        if(response.success){
                            alert('Order saved successfully');
                        } else {
                            alert('Failed to save order');
                        }
                    }
                });
            });
        });

        $("#image-picker").change(function (event) {
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
    <script type="text/javascript" src="{{asset('assets/plugins/parsleyjs/dist/parsley.min.js')}}"></script>
@endpush
