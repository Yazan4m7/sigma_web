@extends('layouts.app' ,[ 'pageSlug' =>'New User'])

@push('css')
    <link href="{{ asset('assets/css/permissions-checkbox.css') }}" rel="stylesheet">
    <style>
        .user-form-card {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f3f6;
        }
        .custom-switch-wrapper {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e3e8ee;
        }
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 26px;
            margin: 0;
        }
        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .switch-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e0;
            transition: .3s;
            border-radius: 26px;
        }
        .switch-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }
        input:checked + .switch-slider {
            background-color: #28a745;
        }
        input:checked + .switch-slider:before {
            transform: translateX(22px);
        }
        .switch-label {
            margin-left: 12px;
            font-size: 14px;
            color: #495057;
            font-weight: 500;
        }
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 8px;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e3e8ee;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="user-form-card">
                <h3 style="margin-bottom: 30px; color: #2c3e50; font-weight: 600;">Create User</h3>

                <form method="POST" action="{{route('new-user')}}" enctype="multipart/form-data">
                    @csrf

                    <!-- Basic Information -->
                    <div class="section-title">Basic Information</div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="first_name" placeholder="Enter first name" value="{{old('first_name')}}">
                                @if ($errors->has('first_name'))
                                    <span class="help-block" style="color: red">{{ $errors->first('first_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="last_name" placeholder="Enter last name" value="{{old('last_name')}}">
                                @if ($errors->has('last_name'))
                                    <span class="help-block" style="color: red">{{ $errors->first('last_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Name Initials</label>
                                <input type="text" class="form-control" name="name_initials" placeholder="E.g. : Y. Moh." value="{{old('name_initials')}}">
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="section-title">Contact Information</div>
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
                                <input type="email" class="form-control" name="email" placeholder="Enter email" value="{{old('email')}}">
                                @if ($errors->has('email'))
                                    <span class="help-block" style="color: red">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Account Settings -->
                    <div class="section-title">Account Settings</div>
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
                                <label>Admin Privileges</label>
                                <div class="custom-switch-wrapper">
                                    <label class="custom-switch">
                                        <input type="checkbox" id="is_admin" name="is_admin">
                                        <span class="switch-slider"></span>
                                    </label>
                                    <span class="switch-label">Grant administrator access</span>
                                </div>
                                @if ($errors->has('is_admin'))
                                    <span class="help-block" style="color: red">{{ $errors->first('is_admin') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Security -->
                    <div class="section-title">Security</div>
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
                    <div class="section-title">Permissions</div>
                    <div class="form-group" id="disable">
                        <div class="permissions-container">
                            <div class="permissions-grid">
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
                    </div>

                    <!-- Profile Image -->
                    <div class="section-title">Profile Image</div>
                    <div class="form-group">
                        <x-user-image-picker current_image="{{ asset('assets/images/avatars/default.png') }}"></x-user-image-picker>
                    </div>

                    <div class="form-actions">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Create User</button>
                    </div>
                </form>
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
