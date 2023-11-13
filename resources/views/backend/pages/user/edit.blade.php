@extends('backend.layouts.master')
<link rel="stylesheet" href="{{ asset('backend/js/plugins/flatpickr/flatpickr.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@section('page_action')  
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('user') }}">Users</a></li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
    @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Update User</h3>
            </div>
            
            <!-- jQuery Validation (.js-validation class is initialized in js/pages/be_forms_validation.min.js which was auto compiled from _js/pages/be_forms_validation.js) -->
            <form class="js-validation" action="{{ url('user/update', $data->user_id) }}" method="POST" onsubmit="return verify_inputs()" id="form" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val_employee_id">Employee ID <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="employee_id" name="employee_id" value="{{ $data->employee_id }}" readonly > 
                                </div>
                                <div class="form-group">
                                    <label for="val-branchId">Branch<span class="text-danger">*</span></label>
                                    <select class="form-control" id="branchId" name="branchId" style="width: 100%" required>
                                        <option value="{{ $data->branch_id }}">{{ $currentBranchName }}</option>
                                        @forelse ($branches as $branch)
                                            @if($data->branch_id != $branch->id)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endif
                                        @empty
                                        <p> </p>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group">
                                <label for="val-departmentId">Department</label>
                                    <select class="form-control" id="departmentId" name="departmentId" style="width: 100%;">
                                        <option value="{{ $data->department_id }}">{{$currentDepartmentName}}</option>
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-designationId">Designation</label>
                                    <select class="form-control" id="designationId" name="designationId" style="width: 100%;">
                                    <option value="{{ $data->designation_id }}">{{$currentDesignationName}}</option>
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val_full_name">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" value="{{ $data->full_name }}" required> 
                                </div>
                                <div class="form-group">
                                    <label for="val_nick_name">Nick Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nick_name" name="nick_name" value="{{ $data->nick_name }}" required> 
                                </div>
                                <div class="form-group">
                                    <label for="val_personal_email">Personal Email<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="personal_email" name="personal_email" value="{{ $data->personal_email }}" required> 
                                    <span id="error_personal_email" style="font-size:13px; color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="val_preferred_email">Preferred Email<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="preferred_email" name="preferred_email" value="{{ $data->email }}" required> 
                                    <span id="error_preferred_email" style="font-size:13px; color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number<span class="text-danger">*</span></label>
                                    <input type="text" class="js-masked-phone form-control" id="phone" name="phone" value="{{ $data->phone_number }}" required>
                                    <small>Format: 171-000-9999</small><br>
                                    <span id="error_phone" style="font-size:13px; color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="val-organization">Previous Organizations</label>
                                    <select class="form-control js-tags" id="organizationName" name="organizationName" style="width: 100%">
                                        <option value="{{ $data->last_organization_id }}">{{$currentOrganizationName}}</option>
                                        @forelse ($organizations as $organization)
                                            @if($data->last_organization_id != $organization->id)
                                                <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                            @endif
                                        @empty
                                        <p> </p>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val_joining_date">Joining date<span class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control" id="joining_date" name="joining_date" value="{{ $data->joining_date }}" data-date-format="Y-m-d" required>
                                </div>
                                <div class="form-group">
                                    <label for="val_career_start_date">Career start date</label>
                                    <input type="text" class="js-flatpickr form-control" id="career_start_date" name="career_start_date" value="{{ $data->career_start_date }}" data-date-format="Y-m-d">
                                </div>
                                <div class="form-group">
                                    <label for="val_photo">Choose a photo</label><br>
                                    <input type="file" name="photo" id="photo" value="{{ $data->image }}" /><br>
                                </div>
                            </div>
                        </div>

                        <!-- Save -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" class="btn btn-alt-primary" id="submit">Update</button>
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
            let personal_email = $('#personal_email').val();
            let preferred_email = $('#preferred_email').val();
            let phone = $('#phone').val();

            let current_personal_email = " <?php echo $data->personal_email; ?>"
            let current_preferred_email = " <?php echo $data->email; ?>"
            let current_phone = " <?php echo $data->phone_number; ?>"
            let flag = 0;
            $.ajax({
                type: 'PATCH',
                async:false,
                url: '{{ url("user/updateUser") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    personal_email: personal_email,
                    preferred_email: preferred_email,
                    phone: phone,
                    current_personal_email: current_personal_email,
                    current_preferred_email: current_preferred_email,
                    current_phone: current_phone
                },
                context: this,
                success: function(response) {
                    if (!response.success) {
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
