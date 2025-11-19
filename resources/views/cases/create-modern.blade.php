@extends('layouts.app', ['pageSlug' => 'New Case'])

@section('content')
    <link rel="stylesheet" href="assets/css/jquery.imagesloader.css" />
    @php
        $color = '#212529';
        $permissions = Cache::get('user' . Auth()->user()->id);
    @endphp
    <style>
        /* ============================================
               MODERN FORM STYLING
               ============================================ */

        .modern-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 1.5rem;
            border: 1px solid #e5e7eb;
        }

        .card-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.25rem 1.75rem;
            border-bottom: none;
        }

        .card-header-modern h5 {
            font-weight: 600;
            font-size: 1.125rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .card-header-modern i {
            font-size: 1.25rem;
        }

        .card-body-modern {
            padding: 1.75rem;
        }

        .form-group-modern {
            margin-bottom: 1.25rem;
        }

        .form-group-modern label {
            font-weight: 600;
            color: #344054;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control-modern {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            background: white;
            width: 100%;
        }

        .form-control-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-control-modern:hover {
            border-color: #d1d5db;
        }

        select.form-control-modern {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 12px;
            padding-right: 2.5rem;
        }

        .mandatory-tag {
            color: #dc3545;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: inline-block;
            font-weight: 500;
        }

        .section-divider {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.1) 0%, rgba(102, 126, 234, 0.3) 50%, rgba(102, 126, 234, 0.1) 100%);
            margin: 1.5rem 0;
        }

        .job-item-card {
            background: #fafbfc;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
            position: relative;
        }

        .job-item-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.08);
        }

        .btn-modern {
            border-radius: 8px;
            padding: 0.625rem 1.25rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .btn-success-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }

        .btn-danger-modern {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white !important;
        }

        .btn-danger-modern:hover {
            transform: translateY(-1px);
        }

        .slctUnitsBtn {
            width: 100%;
            background: white;
            border: 2px solid #e5e7eb !important;
            color: #374151;
            border-radius: 8px;
            padding: 0.625rem 0.875rem !important;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: normal !important;
            text-align: left;
        }

        .slctUnitsBtn:hover {
            border-color: #667eea !important;
            color: #667eea;
            background: #f9fafb !important;
        }

        .abutment-card {
            background: white;
            border: 2px solid #e14eca;
            border-radius: 12px;
            padding: 1.25rem;
            margin: 1rem 0;
        }

        .discount-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 1.25rem;
            margin: 1.25rem 0;
        }

        .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-weight: 500;
            color: #374151;
        }

        .custom-checkbox input[type="checkbox"] {
            width: 1.125rem;
            height: 1.125rem;
            cursor: pointer;
            border-radius: 4px;
        }

        .deleteBtn,
        .deleteBtn2 {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 0.5rem 1rem !important;
            color: white !important;
            transition: all 0.2s ease;
        }

        .deleteBtn:hover,
        .deleteBtn2:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Teeth picker dialog */
        #unitsDialog .modal-footer button[type="button"]:last-child,
        #unitsDialog .modal-footer .btn-primary,
        #unitsDialog .modal-footer button.saveBtn {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: white !important;
            font-size: 16px !important;
            font-weight: 600 !important;
            padding: 12px 32px !important;
            border-radius: 8px !important;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3) !important;
            transition: all 0.2s ease !important;
        }

        #unitsDialog .modal-footer button[type="button"]:last-child:hover,
        #unitsDialog .modal-footer .btn-primary:hover,
        #unitsDialog .modal-footer button.saveBtn:hover {
            background-color: #218838 !important;
            border-color: #218838 !important;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4) !important;
            transform: translateY(-1px);
        }

        #unitsDialog .modal-footer button[data-dismiss="modal"],
        #unitsDialog .modal-footer .btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: white !important;
            font-size: 14px !important;
            padding: 10px 20px !important;
            border-radius: 8px !important;
        }

        @media screen and (max-width: 991px) {
            .modal-content .modal-footer button {
                margin: 15px;
                padding: 10px 40px;
                width: auto;
            }

            .card-body-modern {
                padding: 1.25rem;
            }
        }

        .checked {
            filter: invert(26%) sepia(73%) saturate(492%) hue-rotate(133deg) brightness(94%) contrast(86%);
        }

        .hidden {
            display: none;
        }

        #addJobBtn2 {
            background: linear-gradient(135deg, #ca0399 0%, #970371 100%) !important;
            border: none !important;
        }

        .purpleBorder {
            border: 2px solid #e14eca !important;
            border-radius: 0.75rem;
            background-color: #fff;
        }

        .abutmentsArea {
            flex-basis: 100% !important;
            width: 100% !important;
            margin-top: 15px;
        }

        img {
            max-width: unset;
        }

        .xdsoft_time_box {
            width: 100px !important;
        }

        .xdsoft_datetimepicker {
            padding-right: 50px;
        }

        /* Modern radio buttons */
        .kt-radio {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-right: 1rem;
            font-weight: 500;
            color: #374151;
        }

        .kt-radio input[type="radio"] {
            width: 1.125rem;
            height: 1.125rem;
            cursor: pointer;
        }
    </style>

    <div class="modern-card">
        @if (config('site_vars.environment') == 'testing')
            <form style="padding:0px" class="kt-form" method="POST" enctype="multipart/form-data"
                action="{{ route('create-and-send-case-to') }}">
            @else
                <form style="padding:0px" class="kt-form" method="POST" enctype="multipart/form-data"
                    action="{{ route('new-case-post') }}">
        @endif
        @csrf

        <!-- CASE INFORMATION SECTION -->
        <div class="card-header-modern">
            <h5>
                <i class="fa-solid fa-folder-closed"></i> Case Information
            </h5>
        </div>

        <div class="card-body-modern">
            <div class="row">
                <div class="col-md-3 col-sm-6 form-group-modern">
                    <label>Doctor <span class="mandatory-tag">*</span></label>
                    <select class="selectpicker form-control-modern" name="doctor" data-live-search="true" required
                        title="Select a doctor" data-tap-disabled="true">
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5 col-sm-6 form-group-modern">
                    <label>Patient Name <span class="mandatory-tag">*</span></label>
                    <input class="form-control form-control-modern" type="text" name="patient_name" required />
                </div>

                <div class="col-md-4 col-sm-6 form-group-modern">
                    <label>Case ID <span class="mandatory-tag">*</span></label>
                    <div style="display: flex; align-items: center; gap: 0.25rem;">
                        <span
                            style="font-weight: 500; color: #6b7280;">{{ Auth()->user()->id . '_' . now()->format('Y') }}</span>
                        <input name="caseId1" type="hidden" value="{{ Auth()->user()->id . '_' . now()->format('Y') }}" />
                        <input name="caseId2" placeholder="MM"
                            style="width:45px; border:2px solid #e5e7eb; border-radius: 6px; height:38px; text-align: center; font-weight: 500;"
                            type="text" value="{{ now()->format('m') }}" required />
                        <input name="caseId3" placeholder="DD"
                            style="width:45px; border:2px solid #e5e7eb; border-radius: 6px; height:38px; text-align: center; font-weight: 500;"
                            type="text" value="{{ now()->format('d') }}" required />
                        <span style="font-weight: 500; color: #6b7280;">_</span>
                        <input name="caseId4" placeholder="0000"
                            style="width:70px; border:2px solid #e5e7eb; border-radius: 6px; height:38px; text-align: center; font-weight: 500;"
                            type="text" required />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 col-sm-6 form-group-modern">
                    <label>Impression Type</label>
                    <select class="form-control form-control-modern" name="impression_type" type="text">
                        @foreach ($impressionTypes as $impression)
                            <option value="{{ $impression->id }}">{{ $impression->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5 col-sm-6 form-group-modern">
                    <label>Delivery Date <span class="mandatory-tag">*</span></label>
                    @php
                        $time = new DateTime('tomorrow 13:00');
                        $time = $time->format('d M, Y h:i a');
                    @endphp
                    <input class="form-control form-control-modern SDTP" name="delivery_date" type="text"
                        value="{{ $time }}" required readonly />
                </div>

                <div class="col-md-4 col-sm-6 form-group-modern">
                    <label>Tags</label>
                    <select class="select selectpicker form-control-modern" name="tags[]" multiple
                        data-mdb-placeholder="Tags">
                        @foreach ($tags as $tag)
                            <option style="color:{{ $tag->color }}" value="{{ $tag->id }}">{{ $tag->text }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- JOBS INFORMATION SECTION -->
        <div class="card-header-modern">
            <h5>
                <i class="fa-solid fa-boxes-stacked"></i> Jobs Information
            </h5>
        </div>

        <div class="card-body-modern">
            <div id="" style="" class="repeater jobsRepeater">
                <div data-repeater-list="repeat">
                    <div data-repeater-item class="jobRow">
                        <div class="form-group form-group ">
                            <div data-repeater-list="repeat" class="col-12 padding5px">
                                <div data-repeater-item class="form-group row align-items-center row-item job-item-card">
                                    <div class="col-md-2 form-group-modern">
                                        <label class="kt-label m-label--single">Units <span
                                                class="mandatory-tag">*</span></label>
                                        <input type="hidden" name="units" id="units" class="hiddenUnitsInput"
                                            required>
                                        <button type="button" class="btn slctUnitsBtn" data-toggle="modal"
                                            data-target="#unitsDialog" name="openDialogBtn"
                                            onclick="preOpenDialog(this)">Select Units</button>
                                    </div>

                                    <div class="col-md-2 form-group-modern">
                                        <label>Job Type</label>
                                        <select class="form-control form-control-modern" id="jobType" name="jobType"
                                            onchange="jobTypeChanged(this)">
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 form-group-modern">
                                        <label>Material</label>
                                        <select class="form-control form-control-modern" id="material_id"
                                            name="material_id" onchange="materialChanged(this)">
                                            @foreach ($materials as $m)
                                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 form-group-modern">
                                        <label>Color</label>
                                        <select class="form-control form-control-modern" id="color" name="color">
                                            <option value="0" selected>None</option>
                                            <option value="A1">A1</option>
                                            <option value="A2">A2</option>
                                            <option value="A3">A3</option>
                                            <option value="A3.5">A3.5</option>
                                            <option value="A4">A4</option>
                                            <option value="B1">B1</option>
                                            <option value="B2">B2</option>
                                            <option value="B3">B3</option>
                                            <option value="B4">B4</option>
                                            <option value="C1">C1</option>
                                            <option value="C2">C2</option>
                                            <option value="C3">C3</option>
                                            <option value="C4">C4</option>
                                            <option value="D2">D2</option>
                                            <option value="D3">D3</option>
                                            <option value="D4">D4</option>
                                            <option value="BL1">BL1</option>
                                            <option value="BL2">BL2</option>
                                            <option value="BL3">BL3</option>
                                            <option value="BL4">BL4</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 form-group-modern">
                                        <label>Style</label>
                                        <div class="kt-radio-inline">
                                            <label class="kt-radio">
                                                <input type="radio" class="single" checked="checked" name="style"
                                                    value="Single"> Single
                                                <span></span>
                                            </label>
                                            <label class="kt-radio">
                                                <input type="radio" class="bridge" name="style" value="Bridge">
                                                Bridge
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-2 form-group-modern">
                                        <label>&nbsp;</label>
                                        <button data-repeater-delete class="btn deleteBtn btn-sm btn-danger-modern"
                                            type="button" value="Delete">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </div>

                                    <!-- Abutments Section -->
                                    <div class="col-md-12 abutment abutmentsArea" style="display:none;">
                                        <div class="abutments-repeater abutmentsRepeater">
                                            <div data-repeater-list="abutments" class="dataRepeaterList">
                                                <div data-repeater-item class="abutmentsRow">
                                                    <div class="row abutment-card">
                                                        <div class="col-md-3 form-group-modern">
                                                            <label class="kt-label m-label--single">Abt./Implant
                                                                Units</label>
                                                            <select class="select abutmentsUnitsPicker purpleBorder"
                                                                name="abutmentUnits[]" multiple
                                                                data-mdb-placeholder="Tags">
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 form-group-modern">
                                                            <label class="kt-label m-label--single">Implant Type</label>
                                                            <select class="form-control form-control-modern purpleBorder"
                                                                id="implant" name="implant">
                                                                <option value="0" selected>None</option>
                                                                @foreach ($implants as $implant)
                                                                    <option value="{{ $implant->id }}">
                                                                        {{ $implant->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 form-group-modern">
                                                            <label class="kt-label m-label--single">Abutment Type</label>
                                                            <select class="form-control form-control-modern purpleBorder"
                                                                id="abutment" name="abutment">
                                                                <option value="0" selected>None</option>
                                                                @foreach ($abutments as $abutment)
                                                                    <option value="{{ $abutment->id }}">
                                                                        {{ $abutment->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 form-group-modern">
                                                            <label class="kt-label m-label--single">Code</label>
                                                            <input type="text" name="abutmentCode"
                                                                class="form-control form-control-modern purpleBorder">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label>&nbsp;</label>
                                                            <button data-repeater-delete
                                                                class="btn deleteBtn2 btn-sm btn-danger-modern"
                                                                type="button" value="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="javascript:" data-repeater-create=""
                                                class="btn btn-sm btn-success-modern" id="addJobBtn2"
                                                onClick = "addAbutmentJob(this)">
                                                <i class="fa fa-plus-square"></i> Add Abutment
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="javascript:" data-repeater-create="" class="btn btn-success-modern" id="addJobBtn">
                    <i class="fa fa-plus-square"></i> Add Job
                </a>
            </div>

            <div class="section-divider"></div>

            <!-- DISCOUNTS SECTION -->
            @if (Auth()->user()->is_admin || ($permissions && $permissions->contains('permission_id', 114)))
                <div class="discount-section" style="display:none;" id="discountContainer">
                    <h6 style="margin-bottom: 1rem; font-weight: 600; color: #92400e;">
                        <i class="fa-regular fa-circle-down"></i> Discount Information
                    </h6>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 form-group-modern">
                            <label>Discount Amount (JOD)</label>
                            <input class="form-control form-control-modern" type="number" name="discount_amount"
                                placeholder="0.00" />
                        </div>
                        <div class="col-md-6 col-sm-6 form-group-modern">
                            <label>Discount Reason</label>
                            <input class="form-control form-control-modern" type="text" name="discount_reason"
                                placeholder="Explanation of discount" />
                        </div>
                    </div>
                </div>

                <label class="custom-checkbox" style="margin-bottom: 1.5rem;">
                    <input type="checkbox" class="discountCB" name="discountCB" onclick='toggleDiscountPortion(this)' />
                    <span>Apply Discount</span>
                </label>
            @endif

            <div class="section-divider"></div>

            <!-- NOTES SECTION -->
            <div style="margin-bottom: 1.5rem;">
                <h6 style="margin-bottom: 1rem; font-weight: 600; color: #344054;">
                    <i class="fa fa-sticky-note"></i> Additional Information
                </h6>
                <div class="form-group-modern">
                    <label>Note</label>
                    <textarea class="form-control form-control-modern" name="note" id="exampleTextarea" rows="4"
                        placeholder="Add any additional notes here...">{{ old('note') }}</textarea>
                </div>
            </div>

            <div class="section-divider"></div>

            <!-- ATTACHMENTS SECTION -->
            <div style="margin-bottom: 1.5rem;">
                <h6 style="margin-bottom: 1rem; font-weight: 600; color: #344054;">
                    <i class="fa fa-photo"></i> Attachments
                </h6>
                <div class="form-group-modern">
                    <input type="file" id="images" class="form-control form-control-modern" name="images[]"
                        placeholder="Select images" multiple style="cursor: pointer; padding: 0.5rem;">
                </div>
            </div>

            @if (config('site_vars.environment') == 'testing')
                <div class="col-md-3"
                    style="border: 2px solid #dc3545; padding: 1rem; border-radius: 10px; margin: 1rem 0; background: #fee;">
                    <label for="sendTo" style="font-weight: 600; color: #dc3545;">Testing Helpers:</label>
                    <select class="form-control form-control-modern" id="stageToSendTo" name="stageToSendTo">
                        <option value="1">Design</option>
                        <option value="6">Finishing</option>
                        <option value="7">QC</option>
                        <option value="8">Delivery</option>
                        <option value="10" style="color:green">Completed</option>
                    </select>
                </div>
            @endif

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary-modern btn-modern"
                    style="padding: 0.875rem 2.5rem; font-size: 1rem;">
                    <i class="fa fa-check"></i> Submit Case
                </button>
            </div>
        </div>
        </form>

        <!-- TEETH PICK DIALOG -->
        <div data-repeater-item class="modal fade" id="unitsDialog" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLongTitle" style="display: none;" aria-hidden="true" name="dialog">
            <div class="modal-dialog teethJawsDocument" role="document" style="margin-top: 5px;">
                <div class="modal-content teethJawsDialog">
                    <div class="modal-body" Style="height: 36em;">
                        <input type="hidden" value="success" name="dialogNum" class="dialogTag">
                        @php
                            $startingPosition = 290;
                            $imageSize = 50;
                            $decrement = 45;
                            $teeth = 0;
                            $imageSizeL = 49;
                            $imageSizeM = 35;
                            $leftPadding = 66;
                        @endphp
                        <div class="main-body" style="padding-top: 30px;width:200px;">
                            <img class="jaw upperJaw" alt="upper" src="/assets/teethPics/v2/upper_jaw.png"
                                height=265px style="position: absolute; top: 17px;left: 0px;">
                            <img class="jaw lowerJaw" alt="lower" src="/assets/teethPics/v2/lower_jaw.png"
                                height=280px style="position: absolute; top: 295px;left: 17px;">

                            <img class="teeth" alt="18" src="/assets/teethPics/v2/18.png"
                                height={{ $imageSizeM + 8 }}px style="position: absolute; top: 226px;left: 55px;">
                            @php $teeth = 1; @endphp
                            <img class="teeth" alt="17" src="/assets/teethPics/v2/17.png"
                                height={{ $imageSizeL }}px style="position: absolute; top:183px;left:59px;">
                            @php $teeth = 2; @endphp
                            <img class="teeth" alt="16" src="/assets/teethPics/v2/16.png"
                                height={{ $imageSizeL + 3 }}px style="position: absolute; top: 139px;left:67px;">
                            @php
                                $teeth = 3;
                                $decrement = $decrement - 1.5;
                            @endphp
                            <img class="teeth" alt="15" src="/assets/teethPics/v2/15.png"
                                height={{ $imageSizeM + 1 }}px style="position: absolute; top: 111px;left:79px;">
                            @php $teeth = 4; @endphp
                            <img class="teeth" alt="14" src="/assets/teethPics/v2/14.png"
                                height={{ $imageSizeM + 2 }}px style="position: absolute; top:82px;left:92px;">
                            @php $teeth = 5; @endphp
                            <img class="teeth" alt="13" src="/assets/teethPics/v2/13.png"
                                height={{ $imageSizeM + 6 }}px style="position: absolute; top:53px;left:110px;">
                            @php $teeth = 6; @endphp
                            <img class="teeth" alt="12" src="/assets/teethPics/v2/12.png"
                                height={{ $imageSizeM + 4 }}px style="position: absolute; top: 36px;left: 135px;">
                            @php $teeth = 7; @endphp
                            <img class="teeth" alt="11" src="/assets/teethPics/v2/11.png"
                                height={{ $imageSizeM + 5 }}px style="position: absolute; top: 23.5px;left: 162px;">
                            @php $teeth = 8; @endphp
                            <img class="teeth" alt="21" src="/assets/teethPics/v2/21.png"
                                height={{ $imageSizeM + 5 }}px style="position: absolute; top: 23px;left:200px;">
                            @php $teeth = 9; @endphp
                            <img class="teeth" alt="22" src="/assets/teethPics/v2/22.png"
                                height={{ $imageSizeM + 5 }}px style="position: absolute; top:35px;left: 231px;">
                            @php $teeth = 5; @endphp
                            <img class="teeth" alt="23" src="/assets/teethPics/v2/23.png"
                                height={{ $imageSizeM + 3 }}px style="position: absolute; top: 55px;left: 254px;">
                            @php $teeth = 4; @endphp
                            <img class="teeth" alt="24" src="/assets/teethPics/v2/24.png"
                                height={{ $imageSizeM }}px style="position: absolute; top: 84px;left: 266px;">
                            @php $teeth = 3; @endphp
                            <img class="teeth" alt="25" src="/assets/teethPics/v2/25.png"
                                height={{ $imageSizeM }}px style="position: absolute; top:112px;left:272px;">
                            @php $teeth = 2; @endphp
                            <img class="teeth" alt="26" src="/assets/teethPics/v2/26.png"
                                height={{ $imageSizeL + 1 }}px style="position: absolute; top: 141px;left: 280px;">
                            @php $teeth = 1; @endphp
                            <img class="teeth" alt="27" src="/assets/teethPics/v2/27.png"
                                height={{ $imageSizeL }}px style="position: absolute; top:182px;left: 291px;">
                            @php $teeth = 0; @endphp
                            <img class="teeth" alt="28" src="/assets/teethPics/v2/28.png"
                                height={{ $imageSizeL }}px style="position: absolute; top:227px;left: 291px;">
                            @php
                                $teeth = 16;
                                $startingPosition = 330;
                                $imageSize = 50;
                                $decrement = 45;
                                $teeth = 0;
                                $imageSizeL = 43;
                                $imageSizeM = 35;
                                $leftPadding = 70;
                            @endphp

                            <div class="main-body" style="padding-top: 50px;width:200px;height:450px">
                                <h2 style="padding-left:300%" id="teethSelectedH2"></h2>
                                <img class="teeth" alt="38" src="/assets/teethPics/v2/38.png"
                                    height={{ $imageSizeL + 1 }}px style="position: absolute; top:326px;left: 309px;">
                                @php $teeth = 1; @endphp
                                <img class="teeth" alt="37" src="/assets/teethPics/v2/37.png"
                                    height={{ $imageSizeL + 6 }}px style="position: absolute; top:367px;left:299px;">
                                @php $teeth = 2; @endphp
                                <img class="teeth" alt="36" src="/assets/teethPics/v2/36.png"
                                    height={{ $imageSizeL + 5 }}px style="position: absolute; top:412px;left:285px;">
                                @php
                                    $teeth = 3;
                                    $decrement = $decrement - 1.5;
                                @endphp
                                <img class="teeth" alt="35" src="/assets/teethPics/v2/35.png"
                                    height={{ $imageSizeM }}px style="position: absolute; top: 454px;left:275px;">
                                @php $teeth = 4; @endphp
                                <img class="teeth" alt="34" src="/assets/teethPics/v2/34.png"
                                    height={{ $imageSizeM }}px style="position: absolute; top: 484px;left:263px;">
                                @php $teeth = 5; @endphp
                                <img class="teeth" alt="33" src="/assets/teethPics/v2/33.png"
                                    height={{ $imageSizeM + 1 }}px style="position: absolute; top: 508px;left:247px;">
                                @php $teeth = 6; @endphp
                                <img class="teeth" alt="32" src="/assets/teethPics/v2/32.png"
                                    height={{ $imageSizeM }}px style="position: absolute; top: 527px;left: 229px;">
                                @php $teeth = 7; @endphp
                                <img class="teeth" alt="31" src="/assets/teethPics/v2/31.png"
                                    height={{ $imageSizeM - 3 }}px style="position: absolute; top:538px;left: 203px;">
                                @php $teeth = 8; @endphp
                                <img class="teeth" alt="41" src="/assets/teethPics/v2/41.png"
                                    height={{ $imageSizeM - 2 }}px style="position: absolute; top: 534px;left:176px;">
                                @php $teeth = 9; @endphp
                                <img class="teeth" alt="42" src="/assets/teethPics/v2/42.png"
                                    height={{ $imageSizeM }}px style="position: absolute; top:524px;left: 150px;">
                                @php $teeth = 5; @endphp
                                <img class="teeth" alt="43" src="/assets/teethPics/v2/43.png"
                                    height={{ $imageSizeM }}px style="position: absolute; top: 510px;left: 127px;">
                                @php $teeth = 4; @endphp
                                <img class="teeth" alt="44" src="/assets/teethPics/v2/44.png"
                                    height={{ $imageSizeM }}px style="position: absolute; top: 485px;left: 108px;">
                                @php $teeth = 3; @endphp
                                <img class="teeth" alt="45" src="/assets/teethPics/v2/45.png"
                                    height={{ $imageSizeM + 2 }}px style="position: absolute; top: 455px;left: 88px;">
                                @php $teeth = 2; @endphp
                                <img class="teeth" alt="46" src="/assets/teethPics/v2/46.png"
                                    height={{ $imageSizeL + 4.5 }}px style="position: absolute; top: 415px;left: 68px;">
                                @php $teeth = 1; @endphp
                                <img class="teeth" alt="47" src="/assets/teethPics/v2/47.png"
                                    height={{ $imageSizeL + 5 }}px style="position: absolute; top: 371px;left: 55px;">
                                @php $teeth = 0; @endphp
                                <img class="teeth" alt="48" src="/assets/teethPics/v2/48.png"
                                    height={{ $imageSizeL + 1 }}px style="position: absolute; top: 331px;left:44px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" name="model-footer" style="padding: 12px; display: flex; gap: 8px;">
                        <button type="button" class="btn btn-primary" id="submitDialog" onclick=""
                            style="flex: 2;">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            style="flex: 1;">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery.repeater3.min.js') }}" defer></script>
    <script>
        function initializeSelectPicker() {
            console.log('Initializing selectpicker...');
            if (typeof $.fn.selectpicker === 'undefined') {
                console.warn('Bootstrap Select not loaded, adding form-control fallback.');
                $('.selectpicker').addClass('form-control');
                return;
            }
            if ($.fn.selectpicker.Constructor) {
                $.fn.selectpicker.Constructor.BootstrapVersion = '4';
            }
            $('.selectpicker').each(function() {
                const $select = $(this);
                if ($select.data('selectpicker')) {
                    console.log('Already initialized:', this.name || this.id);
                    return;
                }
                try {
                    $select.selectpicker();
                    console.log('✅ Initialized selectpicker for:', this.name || this.id || this);
                } catch (e) {
                    console.warn('❌ Failed to initialize selectpicker:', e);
                    $select.addClass('form-control');
                }
            });
        }

        $(window).on('load', function() {
            setTimeout(function() {
                initializeSelectPicker();
            }, 500);
        });

        new MutationObserver(function(mutations) {
            for (const m of mutations) {
                if ([...m.addedNodes].some(
                        n => n.nodeType === 1 && (n.matches('.selectpicker') || $(n).find('.selectpicker').length)
                    )) {
                    console.log('Detected new selectpicker in DOM.');
                    initializeSelectPicker();
                    break;
                }
            }
        }).observe(document.body, {
            childList: true,
            subtree: true
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.repeater').repeater({
                repeaters: [{
                    selector: '.abutments-repeater',
                    show: function() {
                        $(this).slideDown();
                    },
                    hide: function(deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                }],
                defaultValues: {},
                show: function() {
                    $(this).slideDown();
                },
                initEmpty: false,
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });

            $(".jobsRepeater").find(".jobRow").first().html("");
            $("#addJobBtn").click();
        });
    </script>

    <script>
        function toggleDiscountPortion(ele) {
            var discountContainer = $("#discountContainer");
            if (ele.checked) {
                discountContainer.slideDown(200);
            } else {
                discountContainer.slideUp(200);
            }
        }

        var teethSelected = [];
        var lstSelectedJobUNName = "";
        var repeaterName = "";

        function materialChanged(materialDD) {
            console.log('Material changed:', $(materialDD).val());
        }

        function jobTypeChanged(jobTypeDD) {
            var thisRowRepeaterName = $(jobTypeDD).attr("name").replace('[jobType]', '');
            var jobTypes = {!! json_encode($types->toArray()) !!};
            var materials = {!! json_encode($materials->toArray()) !!};
            var materialJobTypeRelations = {!! json_encode($jobTypeMaterials->toArray()) !!};

            var repeaterNumber = thisRowRepeaterName.replace('repeat[', '').replace(']', '');
            var colorsDDName = thisRowRepeaterName + "[color]";

            if ($(jobTypeDD).val() == 14) {
                $("[name='" + colorsDDName + "']").parent().parent().parent().show();
            }

            if (repeaterNumber > 1) {
                var implantBox = $("[name='repeat[" + (repeaterNumber - 1) + "][abutments][0][implant]']");
                var abutmentBox = $("[name='repeat[" + (repeaterNumber - 1) + "][abutments][0][abutment]']");
                var abutUnitsBox = $("[name='repeat[" + (repeaterNumber - 1) + "][abutments][0][abutmentUnits][]']");
            } else {
                var implantBox = $("[name='" + thisRowRepeaterName + "[abutments][0][implant]']");
                var abutmentBox = $("[name='" + thisRowRepeaterName + "[abutments][0][abutment]']");
                var abutUnitsBox = $("[name='" + thisRowRepeaterName + "[abutments][0][abutmentUnits][]']");
            }

            var teethSelectedAsArr = $("[name='" + lstSelectedJobUNName + "']").val().split(',');
            var materialBox = $("[name='" + thisRowRepeaterName + "[material_id]']");
            var openDialogBtn = $("[name='" + thisRowRepeaterName + "[openDialogBtn]']");
            var jobTypeSelectedId = $(jobTypeDD).val();
            var jobTypeMaterials = materialJobTypeRelations.filter(element => element.jobtype_id == jobTypeSelectedId);

            var currentlySelectedMaterial = materialBox.val();
            materialBox.empty();
            materialBox.append($("<option></option>").attr("value", "").text("Select Material"));

            $.each(jobTypeMaterials, function(key, value) {
                materialBox.append($("<option></option>")
                    .attr("value", value.material_id)
                    .text(materials.find(x => x.id === value.material_id).name));
            });

            if (currentlySelectedMaterial && jobTypeMaterials.some(jm => jm.material_id == currentlySelectedMaterial)) {
                materialBox.val(currentlySelectedMaterial);
            } else if (jobTypeMaterials.length > 0) {
                materialBox.val(jobTypeMaterials[0].material_id);
            }

            var abutmentsArea = $(jobTypeDD).parent().parent().parent().parent().parent().find(".abutmentsArea");
            var abutmentUnitsBox = $(abutmentsArea).find(".abutmentsUnitsPicker");
            var currentlySelectedUnits = $(jobTypeDD).parent().parent().parent().parent().parent().find(".hiddenUnitsInput")
                .val().split(',');

            if ($(jobTypeDD).find(":selected").val() == 6) {
                $(abutmentBox).attr('required', '');
                $(implantBox).attr('required', '');
                $(abutmentsArea).css("display", "block");

                if (abutmentUnitsBox.hasClass('selectpicker')) {
                    abutmentUnitsBox.selectpicker('destroy');
                }

                abutmentUnitsBox.empty();
                $.each(currentlySelectedUnits, function(index, value) {
                    abutmentUnitsBox.append($("<option></option>")
                        .attr("value", value)
                        .text(value));
                });

                abutmentUnitsBox.selectpicker();
                $(jobTypeDD).attr("readonly", "true");
                $(openDialogBtn).attr("disabled", "true");
            } else {
                $(abutmentBox).removeAttr('required');
                $(implantBox).removeAttr('required');
                $(abutmentsArea).css("display", "none");
                abutmentUnitsBox.val(0);
            }
        }

        function addAbutmentJob(ele) {
            var teethSelectedAsArr = $("[name='" + lstSelectedJobUNName + "']").val().split(',');
            setTimeout(function() {
                var lastAbutmentUnitsBox = $("select[name$='[abutmentUnits][]']").last();

                if (lastAbutmentUnitsBox.hasClass('selectpicker')) {
                    lastAbutmentUnitsBox.selectpicker('destroy');
                }

                lastAbutmentUnitsBox.empty();
                $.each(teethSelectedAsArr, function(index, value) {
                    lastAbutmentUnitsBox.append($("<option></option>")
                        .attr("value", value)
                        .text(value));
                });

                lastAbutmentUnitsBox.selectpicker();
            }, 500);
        }

        $("#submitDialog").click(function() {
            var teethSelectedAsArr = $("[name='" + lstSelectedJobUNName + "']").val().split(',');
            var jobTypeBoxName = repeaterName + "[jobType]";
            var selectBtnName = repeaterName + "[openDialogBtn]";
            var jobTypeBox = $("[name='" + jobTypeBoxName + "']");
            var jobTypes = {!! json_encode($types->toArray()) !!};
            var colorsDDName = repeaterName + "[color]";
            var styleOptionsName = repeaterName + "[style]";

            if (jQuery.inArray("lower", teethSelectedAsArr) !== -1 || jQuery.inArray("upper",
                teethSelectedAsArr) !== -1) {
                jobTypeBox.empty();
                var jawOnlyTypes = jobTypes.filter(element => element.teeth_or_jaw == 1);
                $.each(jawOnlyTypes, function(key, value) {
                    jobTypeBox.append($("<option></option>")
                        .attr("value", value.id)
                        .text(value.name));
                });
                jobTypeChanged(jobTypeBox);
                $("[name='" + colorsDDName + "']").parent().parent().parent().hide();
                $("[name='" + styleOptionsName + "']").val('None');
                $("[name='" + styleOptionsName + "']").parent().parent().parent().hide();
            } else {
                jobTypeBox.empty();
                const jawOnlyTypes = jobTypes.filter(element => element.teeth_or_jaw == 0);
                $.each(jawOnlyTypes, function(key, value) {
                    jobTypeBox.append($("<option></option>")
                        .attr("value", value.id)
                        .text(value.name));
                });
                if (teethSelectedAsArr.length > 1)
                    $("[name='" + styleOptionsName + "'][value='Bridge']").prop("checked", true);
                else
                    $("[name='" + styleOptionsName + "'][value='Single']").prop("checked", true);
                jobTypeChanged(jobTypeBox);
            }

            if (teethSelectedAsArr.length > 0)
                $("[name='" + selectBtnName + "']").html(teethSelectedAsArr.join(","));
            else
                $("[name='" + selectBtnName + "']").html("Select Units");

            $("[name='" + colorsDDName + "']").val($("[name='" + colorsDDName + "'] option:first").val());
            $("#unitsDialog").modal('hide');
            $("#submitDialog").blur();
            setTimeout(function() {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
            }, 300);
        });

        $(".teeth").click(function() {
            if (jQuery.inArray("upper", teethSelected) !== -1) {
                const jawIndex = teethSelected.indexOf("upper");
                teethSelected.splice(jawIndex, 1);
            }
            if (jQuery.inArray("lower", teethSelected) !== -1) {
                const jawIndex = teethSelected.indexOf("lower");
                teethSelected.splice(jawIndex, 1);
            }

            var list = $('.jaw');
            list.removeClass("checked");

            if ($(this).hasClass("checked")) {
                $(this).removeClass("checked");
                var teethNumber = $(this).attr("alt");
                const index = teethSelected.indexOf(teethNumber);
                if (index > -1) {
                    teethSelected.splice(index, 1);
                }
            } else {
                var teethNumber = $(this).attr("alt");
                teethSelected.push(teethNumber);
                $(this).addClass("checked");
            }

            $("[name='" + lstSelectedJobUNName + "']").val(teethSelected);
        });

        $(".jaw").click(function() {
            if ($(this).hasClass("checked")) {
                $(this).removeClass("checked");
                var jaw = $(this).attr("alt");
                const index = teethSelected.indexOf(jaw);
                if (index > -1) {
                    teethSelected.splice(index, 1);
                }
                var unitNumsBox = $("[id=units]:last").attr("name");
                $("[name='" + unitNumsBox + "']").val(teethSelected);
            } else {
                var jaw = $(this).attr("alt");
                $(this).addClass("checked");
                var list = $('.teeth');
                list.removeClass("checked");
                for (var index = 0; index <= teethSelected.length; index++) {
                    if (teethSelected[index] != "lower" && teethSelected[index] != "upper") {
                        teethSelected.splice(index);
                    }
                }
                teethSelected.push(jaw);
            }
            $("[name='" + lstSelectedJobUNName + "']").val(teethSelected);
        });

        function preOpenDialog(element) {
            if (element.name.length == 24) {
                lstSelectedJobUNName = element.name.substr(0, 9) + "[units]";
                repeaterName = element.name.substr(0, 9);
            } else {
                repeaterName = element.name.substr(0, 10);
                lstSelectedJobUNName = element.name.substr(0, 10) + "[units]";
            }
            var currentJobUnits = $("[name='" + lstSelectedJobUNName + "']");
            if (typeof currentJobUnits !== "undefined" && currentJobUnits.val()) {
                teethSelected = currentJobUnits.val().split(',');
            } else {
                teethSelected = [];
            }
            if (teethSelected.length !== 0) {
                var teethPreSelected = currentJobUnits.val().split(',');
                $(".teeth").each(function() {
                    if (jQuery.inArray($(this).attr("alt"), teethPreSelected) !== -1) {
                        $(this).addClass("checked");
                    } else
                        $(this).removeClass("checked");
                });
                $(".jaw").each(function() {
                    if (jQuery.inArray($(this).attr("alt"), teethPreSelected) !== -1)
                        $(this).addClass("checked");
                    else
                        $(this).removeClass("checked");
                });
            } else {
                $(".teeth").removeClass("checked");
                $(".jaw").removeClass("checked");
            }
        }
    </script>

    <script src="{{ asset('assets/js/jquery.imagesloader-1.0.1.js') }}"></script>
    <script src="{{ asset('assets/js/lightgallery.js') }}"></script>
@endpush
