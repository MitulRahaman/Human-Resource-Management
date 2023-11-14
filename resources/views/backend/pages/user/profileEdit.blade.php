@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{asset('backend/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/flatpickr/flatpickr.min.css')}}">
@endsection
@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('user/user') }}">Profile</a></li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Edit Profile</h3>
            </div>
            <div class="js-wizard-simple block block">
                <!-- Step Tabs -->
                <ul class="nav nav-tabs nav-tabs-block nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#wizard-progress-step1" data-toggle="tab">1. Personal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#wizard-progress-step2" data-toggle="tab">2. Academic</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#wizard-progress-step3" data-toggle="tab">3. Banking</a>
                    </li>
                </ul>
                <!-- END Step Tabs -->

                <!-- Form -->
                <form class="js-validation" id='form' action='{{ url('user/profile/' . $user->id . '/update')}}' method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Wizard Progress Bar -->
                    <div class="block-content block-content-sm">
                        <div class="progress" data-wizard="progress" style="height: 8px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <!-- END Wizard Progress Bar -->

                    <!-- Steps Content -->
                    <div class="block-content block-content-full tab-content px-md-5" style="min-height: 300px;">
                        <!-- Step 1 -->
                        <div class="tab-pane active" id="wizard-progress-step1" role="tabpanel">
                            <div class="form-group">
                                <label for="wizard-progress-firstname">Father Name</label>
                                <input class="form-control" type="text" id="father_name" name="father_name">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-lastname">Mother Name</label>
                                <input class="form-control" type="text" id="mother_name" name="mother_name">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-lastname">NID</label>
                                <input class="form-control" type="text" id="nid" name="nid">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-lastname">Birth Certificate</label>
                                <input class="form-control" type="text" id="birth_certificate" name="birth_certificate">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-lastname">Passport No</label>
                                <input class="form-control" type="text" id="passport_no" name="passport_no">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-skills">Gender</label>
                                <select class="form-control" id="gender" name="gender">
                                    @foreach ($const_variable["gender"] as $gender => $value)
                                        <option value="{{$value}}">{{$gender}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-skills">Religion</label>
                                <select class="form-control" id="religion" name="religion">
                                @foreach ($const_variable["religion"] as $religion => $value)
                                        <option value="{{$value}}">{{$religion}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-skills">Blood Group</label>
                                <select class="form-control" id="blood_group" name="blood_group">
                                    @foreach ($const_variable["blood_group"] as $blood_group => $value)
                                        <option value="{{$value}}">{{$blood_group}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group ">
                                <label for="example-flatpickr-default">Date Of Birth</label>
                                <input type="text" class="js-flatpickr form-control bg-white" id="dob" name="dob" placeholder="Y-m-d">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-skills">Marital Status</label>
                                <select class="form-control" id="marital_status" name="marital_status">
                                    @foreach ($const_variable["marital_status"] as $marital_status => $value)
                                        <option value="{{$value}}">{{$marital_status}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-lastname">No of Children</label>
                                <input class="form-control" type="number" id="no_of_children" name="no_of_children">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-lastname">Emergency Contact Name</label>
                                <input class="form-control" type="text" id="emergency_contact_name" name="emergency_contact_name">
                            </div>
                            <div class="form-group">
                                <label for="phone">Emergency Contact Number</label>
                                <input type="number" class="js-masked-phone form-control" id="emergency_contact" name="emergency_contact" placeholder="162-000-0000">
                                <small>Format: 162-000-0000</small><br>
                                <span id="error_phone" style="font-size:13px; color:red"></span>
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-lastname">Relation with Emergency Contact Person</label>
                                <input class="form-control" type="text" id="relation" name="relation">
                            </div>

                        </div>
                        <!-- END Step 1 -->

                        <!-- Step 2 -->
                        <div class="tab-pane" id="wizard-progress-step2" role="tabpanel">
                            <div class="form-group">
                                <label for="wizard-progress-skills">Select Institute</label>
                                <select class="form-control" id="institute_id" name="institute_id">
                                    @foreach ($institutes as $i)
                                        <option value="{{$i->id}}">{{$i->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-skills">Degree</label>
                                <select class="form-control" id="degree_id" name="degree_id">
                                    @foreach ($degree as $d)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-lastname">Major</label>
                                <input class="form-control" type="text" id="major" name="major">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-lastname">Passing Year</label>
                                <select class="form-control" id="year" name="year">
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 25;
                                        $endYear = $currentYear + 5;
                                    @endphp

                                    @for ($year = $startYear; $year <= $endYear; $year++)
                                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <!-- END Step 2 -->

                        <!-- Step 3 -->
                        <div class="tab-pane" id="wizard-progress-step3" role="tabpanel">
                            <div class="form-group">
                                <label for="wizard-progress-skills">Bank</label>
                                <select class="form-control" id="bank_id" name="bank_id">
                                    @foreach ($bank as $b)
                                        <option value="{{$b->id}}">{{$b->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-location">Account Name</label>
                                <input class="form-control" type="text" id="account_name" name="account_name">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-location">Account Number</label>
                                <input class="form-control" type="number" id="account_number" name="account_number">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-location">Branch</label>
                                <input class="form-control" type="text" id="branch" name="branch">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-location">Routing Number</label>
                                <input class="form-control" type="text" id="routing_number" name="routing_number">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-location">Nominee Name</label>
                                <input class="form-control" type="text" id="nominee_name" name="nominee_name">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-location">Nominee NID</label>
                                <input class="form-control" type="text" id="nominee_nid" name="nominee_nid">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-location">Nominee Photo</label>
                                <input class="form-control" type="file" id="nominee_photo" name="nominee_photo">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-location">Relation with Nominee</label>
                                <input class="form-control" type="text" id="nominee_relation" name="nominee_relation">
                            </div>
                            <div class="form-group">
                                <label for="phone">Nominee Contact Number</label>
                                <input type="text" class="js-masked-phone form-control" id="nominee_phone_number" name="nominee_phone_number" placeholder="162-000-0000">
                                <small>Format: 162-000-0000</small><br>
                                <span id="error_nominee_phone" style="font-size:13px; color:red"></span>
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-location">Nominee Email</label>
                                <input class="form-control" type="email" id="nominee_email" name="nominee_email">
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-primary">
                                    <input type="checkbox" class="custom-control-input" id="wizard-progress-terms" name="wizard-progress-terms">
                                    <label class="custom-control-label" for="wizard-progress-terms">Agree with the terms</label>
                                </div>
                            </div>
                        </div>
                        <!-- END Step 3 -->
                    </div>
                    <!-- END Steps Content -->

                    <!-- Steps Navigation -->
                    <div class="block-content block-content-sm block-content-full bg-body-light rounded-bottom">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-alt-primary" data-wizard="prev">
                                    <i class="fa fa-angle-left mr-1"></i> Previous
                                </button>
                            </div>
                            <div class="col-6 text-right">
                                <button type="button" class="btn btn-alt-primary" data-wizard="next">
                                    Next <i class="fa fa-angle-right ml-1"></i>
                                </button>
                                <button type="submit" class="btn btn-primary d-none" data-wizard="finish">
                                    <i class="fa fa-check mr-1"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- END Steps Navigation -->
                </form>
                <!-- END Form -->
            </div>

            <!-- jQuery Validation -->
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
@endsection


@section('js_after')

    <script src="{{asset('backend/js/oneui.core.min.js')}}"></script>
    <script src="{{asset('backend/js/oneui.app.min.js')}}"></script>
    <!-- Page JS Plugins -->
    <script src="{{asset('backend/js/plugins/jquery-bootstrap-wizard/bs4/jquery.bootstrap.wizard.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/jquery-validation/additional-methods.js')}}"></script>

    <script src="{{asset('backend/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
    <!-- Page JS Code -->
    <script src="{{asset('backend/js/pages/be_forms_wizard.min.js')}}"></script>
    <script>jQuery(function(){One.helpers(['flatpickr', 'datepicker', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);});</script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var emergencyContactInput = document.getElementById('emergency_contact');
            var errorPhoneSpan = document.getElementById('error_phone');

            emergencyContactInput.addEventListener('input', function () {
                var phone = this.value;
                var phonePattern = /^\d{10}$/;

                if (!phonePattern.test(phone)) {
                    errorPhoneSpan.innerText = 'Invalid phone number format';
                    emergencyContactInput.setCustomValidity('Invalid phone number format');
                } else {
                    errorPhoneSpan.innerText = '';
                    emergencyContactInput.setCustomValidity('');
                }
            });
            var nomineePhoneNumberInput = document.getElementById('nominee_phone_number');
            var errorNomineePhoneSpan = document.getElementById('error_nominee_phone');

            nomineePhoneNumberInput.addEventListener('input', function () {
                var phone = this.value;
                var phonePattern = /^\d{10}$/;

                if (!phonePattern.test(phone)) {
                    errorNomineePhoneSpan.innerText = 'Invalid phone number format';
                    nomineePhoneNumberInput.setCustomValidity('Invalid phone number format');
                } else {
                    errorNomineePhoneSpan.innerText = '';
                    nomineePhoneNumberInput.setCustomValidity('');
                }
            });
        });
    </script>


@endsection
