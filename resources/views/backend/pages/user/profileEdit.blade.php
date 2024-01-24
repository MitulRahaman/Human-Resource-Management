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
            <div class="js-wizard-validation block block">
                <!-- Step Tabs -->
                <ul class="nav nav-tabs nav-tabs-block nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#wizard-validation-step1" data-toggle="tab">1. Personal Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#wizard-validation-step2" data-toggle="tab">2. Academic Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#wizard-validation-step3" data-toggle="tab">3. Bank Information</a>
                    </li>
                </ul>
                <!-- END Step Tabs -->

                <!-- Form -->
                <form class="js-wizard-validation-form" id='form' action='{{ url('user/profile/' . $user->id . '/update')}}' method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Steps Content -->
                    <div class="block-content block-content-full tab-content px-md-5" style="min-height: 300px;">
                        <!-- Step 1 -->
                        <div class="tab-pane active" id="wizard-validation-step1" role="tabpanel">
                            <div class="form-group">
                                <label for="wizard-validation-firstname">Father Name<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="father_name" name="father_name" value="{{($user->personalInfo)? $user->personalInfo->father_name:''}}" required>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-lastname">Mother Name<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="mother_name" name="mother_name" value="{{($user->personalInfo)? $user->personalInfo->mother_name:''}}" required>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-lastname">NID<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="nid" name="nid" value="{{($user->personalInfo)? $user->personalInfo->nid:''}}" required>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-lastname">Birth Certificate</label>
                                <input class="form-control" type="text" id="birth_certificate" name="birth_certificate" value="{{($user->personalInfo)? $user->personalInfo->birth_certificate:''}}">
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-lastname">Passport No</label>
                                <input class="form-control" type="text" id="passport_no" name="passport_no" value="{{($user->personalInfo)? $user->personalInfo->passport_no:''}}">
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-skills">Gender<span class="text-danger">*</span></label>
                                <select class="form-control" id="gender" name="gender">
                                    @foreach ($const_variable["gender"] as $gender => $value)
                                        <option value="{{$value}}" {{$user->personalInfo? ($user->personalInfo->gender==$value? 'selected':'') :''}}>{{$gender}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-skills">Religion<span class="text-danger">*</span></label>
                                <select class="form-control" id="religion" name="religion">
                                @foreach ($const_variable["religion"] as $religion => $value)
                                        <option value="{{$value}}" {{$user->personalInfo? ($user->personalInfo->religion==$value? 'selected':'') :''}}>{{$religion}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-skills">Blood Group<span class="text-danger">*</span></label>
                                <select class="form-control" id="blood_group" name="blood_group">
                                    @foreach ($const_variable["blood_group"] as $blood_group => $value)
                                        <option value="{{$value}}" {{$user->personalInfo? ($user->personalInfo->blood_group==$value? 'selected':'') :''}} >{{$blood_group}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group ">
                                <label for="example-flatpickr-default">Date Of Birth<span class="text-danger">*</span></label>
                                <input type="text" class="js-flatpickr form-control bg-white " data-date-format="d-m-Y" id="dob" name="dob" placeholder="d-m-Y" value="{{($user->personalInfo)? $user->personalInfo->dob:''}}" required>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-skills">Marital Status<span class="text-danger">*</span></label>
                                <select class="form-control" id="marital_status" name="marital_status">
                                    @foreach ($const_variable["marital_status"] as $marital_status => $value)
                                        <option value="{{$value}}" {{$user->personalInfo? ($user->personalInfo->blood_group==$value? 'selected':'') :''}}>{{$marital_status}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-lastname">No of Children</label>
                                <input class="form-control" type="number" id="no_of_children" name="no_of_children" value="{{($user->personalInfo)? $user->personalInfo->no_of_children:''}}">
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-lastname">Present Address<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="present_address" value="{{$user_address && count($user_address)>0 ? $user_address[0]->address:''}}" name="present_address" required>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-lastname">Permanent Address<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="permanent_address" value="{{$user_address && count($user_address)>1 ? $user_address[1]->address:''}}" name="permanent_address" required>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-lastname">Emergency Contact Person Name 1<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="emergency_contact_name" value="{{ isset($user->emergencyContacts) && count($user->emergencyContacts) > 0 ? $user->emergencyContacts[0]->name : '' }}" name="emergency_contact_name" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Emergency Contact Number 1<span class="text-danger">*</span></label>
                                <input type="number" class="js-masked-phone form-control" id="emergency_contact" name="emergency_contact" value="{{ isset($user->emergencyContacts) && count($user->emergencyContacts) > 0 ? $user->emergencyContacts[0]->phone_number : '' }}" placeholder="Enter contact number" required>
                                <small>Format: 1620000000</small><br>
                                <span id="error_phone" style="font-size:13px; color:red"></span>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-lastname">Relation with Emergency Contact Person 1<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="relation" name="relation" value="{{ isset($user->emergencyContacts) && count($user->emergencyContacts) > 0 ? $user->emergencyContacts[0]->relation : '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-lastname">Emergency Contact Person Name 2<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="emergency_contact_name2" name="emergency_contact_name2" value="{{ isset($user->emergencyContacts) && count($user->emergencyContacts) > 1 ? $user->emergencyContacts[1]->name : '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Emergency Contact Number 2<span class="text-danger">*</span></label>
                                <input type="number" class="js-masked-phone form-control" id="emergency_contact2" name="emergency_contact2" value="{{ isset($user->emergencyContacts) && count($user->emergencyContacts) > 1 ? $user->emergencyContacts[1]->phone_number : '' }}" placeholder="Enter contact number" required>
                                <small>Format: 1620000000</small><br>
                                <span id="error_phone2" style="font-size:13px; color:red"></span>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-lastname">Relation with Emergency Contact Person 2<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="relation2" name="relation2" value="{{ isset($user->emergencyContacts) && count($user->emergencyContacts) > 1 ? $user->emergencyContacts[1]->relation : '' }}" required>
                            </div>

                        </div>
                        <!-- END Step 1 -->

                        <!-- Step 2 -->
                        <div class="tab-pane" id="wizard-validation-step2" role="tabpanel">

                            {{--<button onclick="deleteFormBlock()">Delete</button>--}}
                            <div id="formContainer">
                                <!-- Existing form blocks or other content can go here -->
                            </div>
                            <button type="button" class="btn btn-success" onclick="createFormBlock()">Add</button>
                        </div>
                        <!-- END Step 2 -->

                        <!-- Step 3 -->
                        <div class="tab-pane" id="wizard-validation-step3" role="tabpanel">
                            <div class="form-group">
                                <label for="wizard-validation-skills">Bank</label>
                                <select class="form-control" id="bank_id" name="bank_id">
                                    @foreach ($bank as $b)
                                        <option value="{{$b->id}}" {{$user->bankingInfo? ($user->bankingInfo->bank_id == $b->id? 'selected':'') : ''}}>{{$b->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-location">Account Name</label>
                                <input class="form-control" type="text" id="account_name" name="account_name" value="{{$user->bankingInfo? $user->bankingInfo->account_name:''}}">
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-location">Account Number</label>
                                <input class="form-control" type="number" id="account_number" name="account_number" value="{{$user->bankingInfo? $user->bankingInfo->account_number:''}}">
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-location">Branch</label>
                                <input class="form-control" type="text" id="branch" name="branch" value="{{$user->bankingInfo? $user->bankingInfo->branch:''}}">
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-location">Routing Number</label>
                                <input class="form-control" type="text" id="routing_number" name="routing_number" value="{{$user->bankingInfo? $user->bankingInfo->routing_number:''}}" >
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-location">Nominee Name</label>
                                <input class="form-control" type="text" id="nominee_name" name="nominee_name" value="{{$user->bankingInfo? ($user->bankingInfo->nominees[0]? $user->bankingInfo->nominees[0]->name:''):''}}">
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-location">Nominee NID</label>
                                <input class="form-control" type="text" id="nominee_nid" name="nominee_nid" value="{{$user->bankingInfo? ($user->bankingInfo->nominees[0]? $user->bankingInfo->nominees[0]->nid:''):''}}">
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-location">Nominee Photo</label>
                                <input class="form-control" type="file" id="nominee_photo" name="nominee_photo">
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-location">Relation with Nominee</label>
                                <input class="form-control" type="text" id="nominee_relation" name="nominee_relation" value="{{$user->bankingInfo? ($user->bankingInfo->nominees[0]? $user->bankingInfo->nominees[0]->relation:''):''}}">
                            </div>
                            <div class="form-group">
                                <label for="phone">Nominee Contact Number</label>
                                <input type="text" class="js-masked-phone form-control" id="nominee_phone_number" name="nominee_phone_number" value="{{$user->bankingInfo? ($user->bankingInfo->nominees[0]? $user->bankingInfo->nominees[0]->phone_number:''):''}}" placeholder="Enter contact number">
                                <small>Format: 1620000000</small><br>
                                <span id="error_nominee_phone" style="font-size:13px; color:red"></span>
                            </div>
                            <div class="form-group">
                                <label for="wizard-validation-location">Nominee Email</label>
                                <input class="form-control" type="email" id="nominee_email" name="nominee_email" value="{{$user->bankingInfo? ($user->bankingInfo->nominees[0]? $user->bankingInfo->nominees[0]->email:''):''}}">
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-primary">
                                    <input type="checkbox" class="custom-control-input" id="wizard-progress-terms" name="wizard-progress-terms">
                                    <label class="custom-control-label" for="wizard-validation-terms">Agree with the terms</label>
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
                                    <i class="checker fa fa-check mr-1"></i> Submit
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

            var emergencyContactInput2 = document.getElementById('emergency_contact2');
            var errorPhoneSpan2 = document.getElementById('error_phone2');

            emergencyContactInput2.addEventListener('input', function () {
                var phone = this.value;
                var phonePattern = /^\d{10}$/;

                if (!phonePattern.test(phone)) {
                    errorPhoneSpan2.innerText = 'Invalid phone number format';
                    emergencyContactInput2.setCustomValidity('Invalid phone number format');
                } else {
                    errorPhoneSpan2.innerText = '';
                    emergencyContactInput2.setCustomValidity('');
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
    <script>
        $(document).ready(function () {
            var user = @json($user);
            var academic_info= user.academic_info;
            console.log(academic_info[0].passing_year);
            if(academic_info.length>0)
            {
                existingFormBlock(academic_info);
            }
            else
            {
                createFormBlock();
            }
        });

        function existingFormBlock(academic_info) {
            var currentYear = new Date().getFullYear();
            var startYear = currentYear - 25;
            var endYear = currentYear + 5;
            for (var i=0; i<academic_info.length; i=i+1)
            {
                console.log(academic_info[i].id);
                var formBlock = '<div class="form-block">' +
                    '<div class="form-group">' +
                    '<label for="wizard-progress-skills">Institute<span class="text-danger">*</span></label>' +
                    '<select class="form-control" id="institute_id" name="institute_id[]">' +
                    '@foreach ($institutes as $institute)' +
                    '<option value="{{$institute->id}}" ' + (academic_info[i]["institute_id"] == {{$institute->id}} ? "selected" : "") + '>{{$institute->name}}</option>' +
                    '@endforeach' +
                    '</select>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="wizard-progress-skills">Degree<span class="text-danger">*</span></label>' +
                    '<select class="form-control" id="degree_id" name="degree_id[]">' +
                    '@foreach ($degree as $d)' +
                    '<option value="{{$d->id}}"' + (academic_info[i]["degree_id"] == {{$d->id}} ? "selected" : "") + '>{{$d->name}}</option>' +
                    '@endforeach' +
                    '</select>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="wizard-progress-lastname">Major<span class="text-danger">*</span></label>' +
                    '<input class="form-control" type="text" id="major" value="'+ academic_info[i]["major"] +'" name="major[]" required >' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="wizard-progress-lastname">GPA<span class="text-danger">*</span></label>' +
                    '<input class="form-control" type="text" id="gpa" value="'+ academic_info[i]["gpa"] +'" name="gpa[]" required >' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="wizard-progress-lastname">Passing Year<span class="text-danger">*</span></label>' +
                    '<select class="form-control" id="year" name="year[]">';

                for (var year = startYear; year <= endYear; year++) {
                    formBlock += '<option value="' + year + '" ' + (year == academic_info[i]["passing_year"] ? 'selected' : '') + '>' + year + '</option>';
                }

                formBlock += '</select>' +
                    '</div>' +
                    '<button type="button" class="btn btn-danger mb-4" onclick="removeFormBlock('+academic_info[i].id+', this)">Remove</button>' +
                    '</div>';
                $('#formContainer').append(formBlock);

            }

        }

        function createFormBlock() {
            var currentYear = new Date().getFullYear();
            var startYear = currentYear - 25;
            var endYear = currentYear + 5;
            var formBlock = '<div class="form-block">' +
                '<div class="form-group">' +
                '<label for="wizard-progress-skills">Select Institute<span class="text-danger">*</span></label>' +
                '<select class="form-control" id="institute_id" name="institute_id[]">' +
                '@foreach ($institutes as $institute)' +
                '<option value="{{$institute->id}}">{{$institute->name}}</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="wizard-progress-skills">Degree<span class="text-danger">*</span></label>' +
                '<select class="form-control" id="degree_id" name="degree_id[]">' +
                '@foreach ($degree as $d)' +
                '<option value="{{$d->id}}">{{$d->name}}</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="wizard-progress-lastname">Major<span class="text-danger">*</span></label>' +
                '<input class="form-control" type="text" id="major" name="major[]" required >' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="wizard-progress-lastname">GPA<span class="text-danger">*</span></label>' +
                '<input class="form-control" type="text" id="gpa" name="gpa[]" required >' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="wizard-progress-lastname">Passing Year<span class="text-danger">*</span></label>' +
                '<select class="form-control" id="year" name="year[]">';

            for (var year = startYear; year <= endYear; year++) {
                formBlock += '<option value="' + year + '" ' + (year == currentYear ? 'selected' : '') + '>' + year + '</option>';
            }

            formBlock += '</select>' +
                '</div>' +
                '<button type="button" class="btn btn-danger mb-4" onclick="removeFormBlock(null,this)">Remove</button>' +
                '</div>';
            $('#formContainer').append(formBlock);
        }

        function removeFormBlock(id,button) {
            console.log(id);
            $.ajax({
                type: 'delete',
                url: "{{ url('user/profile/:id/delete_academic_info') }}".replace(':id', id),
                async: false,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    One.helpers('notify', {type: 'success', icon: 'fa fa-check mr-1', message: 'Academic Info updated!'});
                },
                error: function() {
                    One.helpers('notify', {type: 'danger', icon: 'fa fa-check mr-1', message: 'Academic Info not updated!'});
                }
            });
            $(button).closest('.form-block').remove();
        }
    </script>


@endsection
