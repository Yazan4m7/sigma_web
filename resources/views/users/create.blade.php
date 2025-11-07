@extends('layouts.app' ,[ 'pageSlug' =>'New User'])

@push('css')
    <link href="{{ asset('assets/css/permissions-checkbox.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div class="row card">
        <div class="col-lg-12 col-sm-12">
            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Create User
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                <form class="kt-form" method="POST" action="{{route('new-user')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="kt-portlet__body">
                        <!-- Basic Information -->
                        <h5 class="text-muted mb-3">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" name="first_name" placeholder="Enter first name" value="{{old('first_name')}}">
                                    @if ($errors->has('first_name'))
                                        <span class="help-block" style="color: red">{{ $errors->first('first_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" name="last_name" placeholder="Enter last name" value="{{old('last_name')}}">
                                    @if ($errors->has('last_name'))
                                        <span class="help-block" style="color: red">{{ $errors->first('last_name') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Name Initials</label>
                            <input type="text" class="form-control" name="name_initials" placeholder="E.g. : Y. Moh." value="{{old('name_initials')}}">
                        </div>

                        <!-- Contact Information -->
                        <h5 class="text-muted mb-3 mt-4">Contact Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input class="form-control" type="tel" name="phone" placeholder="Enter phone number" value="{{old('phone')}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" class="form-control" name="email" aria-describedby="emailHelp" placeholder="Enter email" value="{{old('email')}}">
                                    @if ($errors->has('email'))
                                        <span class="help-block" style="color: red">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Account Settings -->
                        <h5 class="text-muted mb-3 mt-4">Account Settings</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" name="username" placeholder="Enter username" value="{{old('username')}}">
                                    @if ($errors->has('username'))
                                        <span class="help-block" style="color: red">{{ $errors->first('username') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_admin">Admin Privileges</label>
                                    <div>
                                        <input type="checkbox" class="form-control" id="is_admin" name="is_admin" style="width: 20px; display: inline-block;">
                                        <span class="ml-2 text-muted">Grant administrator access</span>
                                    </div>
                                    @if ($errors->has('is_admin'))
                                        <span class="help-block" style="color: red">{{ $errors->first('is_admin') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Security -->
                        <h5 class="text-muted mb-3 mt-4">Security</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Enter password">
                                    @if ($errors->has('password'))
                                        <span class="help-block" style="color: red">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm password">
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block" style="color: red">{{ $errors->first('password_confirmation') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Permissions -->
                        <h5 class="text-muted mb-3 mt-4">Permissions</h5>
                        <div class="form-group" id="disable">
                            <label for="Permission">User Permissions</label>
                            <div class="permissions-container">
                                @foreach($permissions as $perm)
                                    <div class="permission-item">
                                        <input type="checkbox"
                                               class="permission-checkbox"
                                               id="perm-{{$perm->id}}"
                                               name="permission[]"
                                               value="{{$perm->id}}">
                                        <label for="perm-{{$perm->id}}" class="permission-label">
                                            <span class="permission-icon"></span>
                                            <span class="permission-name">{{$perm->name}}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Profile Image -->
                        <h5 class="text-muted mb-3 mt-4">Profile Image</h5>
                        <div class="form-group">
                            <x-user-image-picker></x-user-image-picker>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </div>
                </form>

                <!--end::Form-->
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        // Check if permission 131 (delivery driver) is selected
        function checkDeliveryDriverPermission() {
            if ($('#is_admin').is(':checked')) {
                $('.delivery-driver-section').hide();
                return;
            }

            // Check if permission ID 131 is selected (using checkbox now)
            const hasDeliveryPermission = $('#perm-131').is(':checked');

            if (hasDeliveryPermission) {
                $('.delivery-driver-section').show();
            } else {
                $('.delivery-driver-section').hide();
            }
        }

        // Initialize on page load
        $(document).ready(function() {
            // Set up driver image preview
            $('#driver-image').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $('#driver-image-preview').attr('src', event.target.result);
                        $('.preview-container').show();
                    };
                    reader.readAsDataURL(file);

                    // Update file input label with selected filename
                    $(this).next('.custom-file-label').html(file.name);
                }
            });

            // Check delivery driver permission on page load
            checkDeliveryDriverPermission();

            // Check delivery driver permission when permissions change
            $('.permission-checkbox').on('change', function() {
                checkDeliveryDriverPermission();
            });
        });

        $('#is_admin').on('change', function() {
            if(this.checked){
                $('.permission-checkbox').prop('disabled', true);
                $('#disable').css('visibility', 'hidden');
                // Hide delivery driver section if admin
                $('.delivery-driver-section').hide();
            } else {
                $('.permission-checkbox').prop('disabled', false);
                $('#disable').css('visibility', 'visible');
                // Recheck permissions
                checkDeliveryDriverPermission();
            }
        });

        $('select[name="position"]').on('change', function() {
            var selected = $(this).find('option:selected');
            var extra = selected.data('content');
            if (extra == 'B') {
                $('#TypeB').removeAttr('hidden')
            } else {
                $('#TypeB').prop('hidden', true)
            }
            console.log(extra)
        })
    </script>
@endpush
