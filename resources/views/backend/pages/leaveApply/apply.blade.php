@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/ion-rangeslider/css/ion.rangeSlider.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style >
        .spinner {
            display: none;
        }
    </style>
@endsection
@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('leaveApply/apply') }}">Leave Apply</a></li>
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
            <form class="js-validation form-prevent-multiple-submission" action="{{ url('leaveApply/store') }}" method="POST" id="form" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-val_leave_type_id">Select Leave Type<span class="text-danger">*</span></label>
                                    <select class="form-control input-prevent-multiple-submission" id="leaveTypeId" name="leaveTypeId" style="width: 100%" required>
                                        @forelse ($leaveTypes as $leaveType)
                                        <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                        @empty
                                        <p> </p>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val_leave_date">Select Leave date<span class="text-danger">*</span></label>
                                    <div class="input-daterange input-group" data-date-format="dd/mm/yyyy"  data-autoclose="true" data-today-highlight="true">
                                        <input type="text" class="form-control input-prevent-multiple-submission" id="startDate" name="startDate" placeholder="From"  data-autoclose="true" data-today-highlight="true" required>
                                        <div class="input-group-prepend input-group-append">
                                            <span class="input-group-text font-w600">
                                                <i class="fa fa-fw fa-arrow-right"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control input-prevent-multiple-submission" id="endDate" name="endDate" placeholder="To"  data-autoclose="true" data-today-highlight="true" required>
                                    </div>
                                    <span id="error_date" style="font-size:13px; color:red"></span>
                                </div>

                                <div class="form-group">
                                    <label for="val_reason">Please tell your reason<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-prevent-multiple-submission" id="reason" name="reason" required>
                                </div>
                                <div class="form-group">
                                    <label for="val_photo">Select File</label><br>
                                    <input type="file" class="input-prevent-multiple-submission" name="photo[]" id="photo[]" multiple /><br>
                                </div>
                                <input type="hidden" id="totalLeave" name="totalLeave" min="1" max="90" value="">
                            </div>
                        </div>

                        <!-- Save -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" class="btn btn-alt-primary button-prevent-multiple-submission" id="submit">
                                    <i class="spinner fa fa-spinner fa-spin"></i>Apply
                                </button>
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
            if(total < 181) {
                $('#totalLeave').val(total);
                document.getElementById('error_date').innerHTML = "";
                $('#submit').attr('disabled', false);
            } else {
                document.getElementById('error_date').innerHTML = "total leave must be less than or equal 180 days";
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
        $('.form-prevent-multiple-submission').on('submit',function() {
            $('.button-prevent-multiple-submission').attr('disabled', 'true');
            $('.spinner').show();
        })
        $('.input-prevent-multiple-submission').on('keypress',function() {
            $('.button-prevent-multiple-submission').removeAttr('disabled');
            $('.spinner').hide();
        })
        $('.input-prevent-multiple-submission').on('change' ,function() {
            $('.button-prevent-multiple-submission').removeAttr('disabled');
            $('.spinner').hide();
        })
    </script>
@endsection
