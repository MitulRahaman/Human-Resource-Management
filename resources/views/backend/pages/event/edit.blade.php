@extends('backend.layouts.master')
<link rel="stylesheet" href="{{ asset('backend/js/plugins/flatpickr/flatpickr.min.css') }}">

<link rel="stylesheet" href="{{ asset('backend/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/js/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/js/plugins/ion-rangeslider/css/ion.rangeSlider.css') }}">
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
            <form class="js-validation" action="{{ url('leaveApply/update', $leave->id) }}" method="POST" id="form">
                @csrf
                @method('patch')
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-val_leave_type_id">Select Leave Type<span class="text-danger">*</span></label>
                                    <select class="form-control" id="leaveTypeId" name="leaveTypeId" style="width: 100%" required>
                                        @foreach ($leaveTypes as $leaveType)
                                        <option value="{{ $leaveType->id }}" {{ $leave->leave_type_id == $leaveType->id ? 'selected' : '' }}>{{ $leaveType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val_joining_date">Select Leave date<span class="text-danger">*</span></label>
                                    <div class="input-daterange input-group" data-date-format="dd/mm/yyyy"  data-autoclose="true" data-today-highlight="true">
                                        <input type="text" class="form-control" id="startDate" name="startDate" value = {{ date('d/m/Y', strtotime($leave->start_date)) }}  data-autoclose="true" data-today-highlight="true" required>
                                        <div class="input-group-prepend input-group-append">
                                            <span class="input-group-text font-w600">
                                                <i class="fa fa-fw fa-arrow-right"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="endDate" name="endDate" value="{{ date('d/m/Y', strtotime($leave->end_date)) }}"  data-autoclose="true" data-today-highlight="true" required>
                                        <span id="error_date" style="font-size:13px; color:red"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="val_reason">Please tell your reason<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="reason" name="reason" value="{{ $leave->reason }}" required>
                                </div>
                                <input type="hidden" id="totalLeave" name="totalLeave" value="">
                            </div>
                        </div>

                        <!-- Save -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" disabled="true" class="btn btn-alt-primary" id="submit">Save</button>
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

    <script>jQuery(function(){One.helpers(['flatpickr', 'datepicker', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);});</script>


    <script>
        $(document).ready(function() {
            $('.js-tags').select2({
                tags: true
            });
        });

        function daysdifference(firstDate, secondDate){
            var startDay = new Date(firstDate);
            var endDay = new Date(secondDate);
            var millisBetween = startDay.getTime() - endDay.getTime();
            var days = millisBetween / (1000 * 3600 * 24);
            return Math.round(Math.abs(days)) + 1;
        }

        $('#startDate').change(function() {
            let startDate = $('#startDate').val();
            startDate = startDate.split("/");
            newStartDate = startDate[1] + '/' + startDate[0] + '/' + startDate[2];

            let endDate = $('#endDate').val();
            endDate = endDate.split("/");
            newEndDate = endDate[1] + '/' + endDate[0] + '/' + endDate[2];

            total = daysdifference(newStartDate, newEndDate);
            if(total < 90) {
                $('#totalLeave').val(total);
                document.getElementById('error_date').innerHTML = "";
                $('#submit').attr('disabled', false);
            } else {
                document.getElementById('error_date').innerHTML = "total leave must be less than 90 days";
                $('#submit').attr('disabled', true);
            }
        });

        $('#endDate').change(function() {
            let startDate = $('#startDate').val();
            startDate = startDate.split("/");
            newStartDate = startDate[1] + '/' + startDate[0] + '/' + startDate[2];

            let endDate = $('#endDate').val();
            endDate = endDate.split("/");
            newEndDate = endDate[1] + '/' + endDate[0] + '/' + endDate[2];

            total = daysdifference(newStartDate, newEndDate);
            if(total < 90) {
                $('#totalLeave').val(total);
                document.getElementById('error_date').innerHTML = "";
                $('#submit').attr('disabled', false);
            } else {
                document.getElementById('error_date').innerHTML = "total leave must be less than 90 days";
                $('#submit').attr('disabled', true);
            }
        });

    </script>
@endsection
