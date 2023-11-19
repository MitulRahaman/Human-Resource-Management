@extends('backend.layouts.master')
<link rel="stylesheet" href="{{ asset('backend/js/plugins/flatpickr/flatpickr.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@section('page_action')  
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('user') }}">Users</a></li>
            <li class="breadcrumb-item">Add</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
    @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Add User</h3>
            </div>
            
            <!-- jQuery Validation (.js-validation class is initialized in js/pages/be_forms_validation.min.js which was auto compiled from _js/pages/be_forms_validation.js) -->
            <form class="js-validation" action="{{ url('user/store') }}" method="POST" onsubmit="return verify_inputs()" id="form" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val_employee_id">Employee ID <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="employee_id" name="employee_id" placeholder="Enter Employee id.." required> 
                                    <span id="error_employee_id" style="font-size:13px; color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="val-branchId">Branch<span class="text-danger">*</span></label>
                                    <select class="form-control" id="branchId" name="branchId" style="width: 100%" required>
                                        <option></option>
                                        @forelse ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @empty
                                        <p> </p>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-departmentId">Department</label>
                                    <select class="form-control" id="departmentId" name="departmentId" style="width: 100%;">
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-designationId">Designation</label>
                                    <select class="form-control" id="designationId" name="designationId" style="width: 100%;">
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val_full_name">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter full name.." required> 
                                </div>
                                <div class="form-group">
                                    <label for="val_nick_name">Nick Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nick_name" name="nick_name" placeholder="Enter nick name.." required> 
                                </div>
                                <div class="form-group">
                                    <label for="val_personal_email">Personal Email<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="personal_email" name="personal_email" placeholder="name@gmail.com" required> 
                                    <span id="error_personal_email" style="font-size:13px; color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="val_preferred_email">Preferred Email<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="preferred_email" name="preferred_email" placeholder="name@appnap.io" required> 
                                    <span id="error_preferred_email" style="font-size:13px; color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number<span class="text-danger">*</span></label>
                                    <input type="text" class="js-masked-phone form-control" id="phone" name="phone" placeholder="171-000-9999" required>
                                    <small>Format: 171-000-9999</small><br>
                                    <span id="error_phone" style="font-size:13px; color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="val-organization">Previous Organizations</label>
                                    <select class="form-control js-tags" id="organizationName" name="organizationName" style="width: 100%;" data-placeholder="Select Organizations..">
                                    <option></option>
                                        @forelse ($organizations as $organization)
                                        <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                        @empty
                                        <p> </p>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val_joining_date">Joining date<span class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control" id="joining_date" name="joining_date" placeholder="dd-mm-YYYY" data-date-format="d-m-Y" required>
                                </div>
                                <div class="form-group">
                                    <label for="val_career_start_date">Career start date</label>
                                    <input type="text" class="js-flatpickr form-control" id="career_start_date" name="career_start_date" placeholder="dd-mm-YYYY" data-date-format="d-m-Y">
                                </div>
                                <div class="form-group">
                                    <label for="val_photo">Choose a photo</label><br>
                                    <input type="file" name="photo" id="photo" /><br>
                                </div>
                            </div>
                        </div>

                        <!-- Save -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" class="btn btn-alt-primary" id="submit">Save</button>
                            </div>
                        </div>
                        <!-- END Save -->
                    </div>
                </div>
            </form>
            <!-- End jQuery Validation -->
        </div>
    </div>

@endsection

@section('js_after')
    <script src="{{ asset('backend/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/jquery-validation/additional-methods.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>jQuery(function(){One.helpers(['flatpickr', 'datepicker', 'masked-inputs', 'select2']);});</script>


    <script> 
        $(document).ready(function() {
            $('.js-tags').select2({
                tags: true
            });
        });

        $('#branchId').change(function() {
            let branchId = $('#branchId').val();
            var selectDept = $('#departmentId');
            var selectDesg = $('#designationId');
            $.ajax({
                type: 'POST',
                async:false,
                url: '{{ url("user/getDeptDesg") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    branchId: branchId,
                },
                success: function(response) {
                    var deptOptions = [];
                    var desgOptions = [];
                    if(response.length){
                        for( item in response[0] ) {
                            html = '<option value="' + response[0][item] + '">' + response[1][item] + '</option>';
                            deptOptions[deptOptions.length] = html;
                        }
                        selectDept.empty().append( deptOptions.join('') );

                        for( item in response[2] ) {
                            html = '<option value="' + response[2][item] + '">' + response[3][item] + '</option>';
                            desgOptions[desgOptions.length] = html;
                        }
                        selectDesg.empty().append( desgOptions.join('') );

                    } else {
                        deptOptions[deptOptions.length] = '<option value=""></option>'
                        selectDept.empty().append( deptOptions.join('') );

                        desgOptions[desgOptions.length] = '<option value=""></option>'
                        selectDesg.empty().append( desgOptions.join('') );
                    }
                },
            });
        });


        function verify_inputs(e){
            let employee_id = $('#employee_id').val();
            let personal_email = $('#personal_email').val();
            let preferred_email = $('#preferred_email').val();
            let phone = $('#phone').val();
            let flag = 0;
            $.ajax({
                type: 'POST',
                async:false,
                url: '{{ url("user/verifyUser") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    employee_id: employee_id,
                    personal_email: personal_email,
                    preferred_email: preferred_email,
                    phone: phone
                },
                context: this,
                success: function(response) {
                    if (!response.success) {
                        if(response.error_employee_id) {
                            flag = 0;
                            document.getElementById('error_employee_id').innerHTML = response.error_employee_id;
                        } else {
                            document.getElementById('error_employee_id').innerHTML = "";
                        }
                        if(response.error_personal_email) {
                            flag = 0;
                            document.getElementById('error_personal_email').innerHTML = response.error_personal_email;
                        } else {
                            document.getElementById('error_personal_email').innerHTML = "";
                        }
                        if(response.error_preferred_email) {
                            flag = 0;
                            document.getElementById('error_preferred_email').innerHTML = response.error_preferred_email;
                        } else {
                            document.getElementById('error_preferred_email').innerHTML = "";
                        }
                        if(response.error_phone) {
                            flag = 0;
                            document.getElementById('error_phone').innerHTML = response.error_phone;
                        } else {
                            document.getElementById('error_phone').innerHTML = "";
                        }
                    }
                    else{
                        flag = 1;
                        document.getElementById('error_employee_id').innerHTML = "";
                        document.getElementById('error_personal_email').innerHTML = "";
                        document.getElementById('error_preferred_email').innerHTML = "";
                        document.getElementById('error_phone').innerHTML = "";
                    }
                },
            });
            if(flag)
                return true;
            else 
                return false;
        } 
    </script>
@endsection
