@extends('backend.layouts.master')
<link rel="stylesheet" href="{{ asset('backend/js/plugins/flatpickr/flatpickr.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@section('page_action')  
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('leaveApply') }}">Leave Apply</a></li>
            <li class="breadcrumb-item">Add</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
    @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Apply for Leave</h3>
            </div>
            
            <!-- jQuery Validation (.js-validation class is initialized in js/pages/be_forms_validation.min.js which was auto compiled from _js/pages/be_forms_validation.js) -->
            <form class="js-validation" action="{{ url('leaveApply/store') }}" method="POST" id="form">
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-val_leave_type_id">Select Leave Type<span class="text-danger">*</span></label>
                                    <select class="form-control" id="leaveTypeId" name="leaveTypeId" style="width: 100%" required>
                                        @forelse ($leaveTypes as $leaveType)
                                        <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                        @empty
                                        <p> </p>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val_joining_date">Select Leave date<span class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control bg-white" id="date-range" name="date-range" data-date-format="d-m-Y" placeholder="Select Date Range" data-mode="range" data-min-date="today" required>
                                </div>
                                <div class="form-group">
                                    <label for="val_reason">Please tell your reason<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="reason" name="reason" required>
                                </div>
                                <input type="hidden" id="startDate" name="startDate" value="">
                                <input type="hidden" id="endDate" name="endDate" value="">
                                <input type="hidden" id="totalLeave" name="totalLeave" value="">
                            </div>
                        </div>

                        <!-- Save -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" class="btn btn-alt-primary" id="submit">Apply</button>
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

        $('#date-range').change(function() {
            let date = $('#date-range').val();
            let startDate;
            let endDate;
            let total = 0;

            if(date.indexOf("t") != -1) {
                startDate = date.substring(0, 10);
                endDate = date.substring(14);
                startDate = startDate.split("-").reverse().join("-");
                endDate = endDate.split("-").reverse().join("-");
                total = (Date.parse(endDate) - Date.parse(startDate))/86400000 + 1 ;
            } else {
                startDate = date.substring(0, 10);
                endDate = startDate;
                total = 1;
            }
            $('#totalLeave').val(total);
            $('#startDate').val(startDate);
            $('#endDate').val(endDate);
        });

    </script>
@endsection
