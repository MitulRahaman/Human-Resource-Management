@extends('backend.layouts.master')
@section('css_after')
<link rel="stylesheet" href="{{ asset('backend/js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/ion-rangeslider/css/ion.rangeSlider.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/select2/css/select2.min.css') }}">
        <style >
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #1E8FE6;
            color: #0A1C2A;
        }
        .spinner {
            display: none;
        }
    </style>
@endsection
@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('event/manage') }}">Events</a></li>
            <li class="breadcrumb-item">Create</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
    @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Create Event</h3>
            </div>

            <!-- jQuery Validation (.js-validation class is initialized in js/pages/be_forms_validation.min.js which was auto compiled from _js/pages/be_forms_validation.js) -->
            <form class="js-validation form-prevent-multiple-submission" action="{{ url('event/store') }}" method="POST" id="form" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-title">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-prevent-multiple-submission" id="title" name="title" value="{{ old('title') }}"  placeholder="Give a title.." required>
                                </div>
                                <div class="form-group">
                                    <label for="val-branch">Branch<span class="text-danger">*</span></label>
                                    <select class="form-control input-prevent-multiple-submission" id="branchId" name="branchId" style="width: 100%" required>
                                        <option></option>
                                        @forelse ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @empty
                                        <p> </p>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-department">Department<span class="text-danger">*</span></label>
                                    <select class="js-select2 form-control input-prevent-multiple-submission" id="departmentId" name="departmentId[]" style="width: 100%;" multiple required>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-participant">Participant<span class="text-danger">*</span></label>
                                    <select class="js-select2 form-control input-prevent-multiple-submission" id="participantId" name="participantId[]" style="width: 100%;" data-placeholder="Choose Participant for the Event.." multiple required>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val_leave_date">Date<span class="text-danger">*</span></label>
                                    <div class="input-daterange input-group" data-date-format="dd/mm/yyyy"  data-autoclose="true" data-today-highlight="true">
                                        <input type="text" class="form-control input-prevent-multiple-submission" id="startDate" name="startDate" placeholder="d/m/Y"  data-autoclose="true" data-today-highlight="true" required>
                                        <div class="input-group-prepend input-group-append">
                                            <span class="input-group-text font-w600">
                                                <i class="fa fa-fw fa-arrow-right"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control input-prevent-multiple-submission" id="endDate" name="endDate" placeholder="d/m/Y"  data-autoclose="true" data-today-highlight="true">
                                    </div>
                                    <span id="error_date" style="font-size:13px; color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="val-description">Description</label>
                                    <textarea class="form-control input-prevent-multiple-submission" id="description" name="description" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="val_photo">Itinerary</label><br>
                                    <input type="file" class="input-prevent-multiple-submission" name="photo" id="photo" /><br>
                                </div>
                            </div>
                        </div>

                        <!-- Save -->
                        <div class="row items-push">
                            <div class="col-lg-6 offset-lg-5">
                                <button type="submit" class="btn btn-alt-primary button-prevent-multiple-submission" id="submit">
                                    <i class="spinner fa fa-spinner fa-spin"></i>Create
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
    <script src="{{ asset('backend/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>jQuery(function(){One.helpers(['flatpickr', 'datepicker', 'select2', 'rangeslider']);});</script>

    <script>
        $('#branchId').change(function() {
            let branchId = $('#branchId').val();
            var selectDept = $('#departmentId');
            var selectPart = $('#participantId');
            $.ajax({
                type: 'POST',
                async:false,
                url: '{{ url("event/getDeptPart") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    branchId: branchId,
                },
                success: function(response) {
                    var deptOptions = [];
                    var partOptions = [];
                    if(response.length){
                        for( item in response[0] ) {
                            html = '<option value="' + response[0][item] + '">' + response[1][item] + '</option>';
                            deptOptions[deptOptions.length] = html;
                        }
                        selectDept.empty().append( deptOptions.join('') );

                        for( item in response[2] ) {
                            html = '<option value="' + response[2][item] + '">' + response[3][item] + '</option>';
                            partOptions[partOptions.length] = html;
                        }
                        selectPart.empty().append( partOptions.join('') );

                    } else {
                        deptOptions[deptOptions.length] = '<option value=""></option>'
                        selectDept.empty().append( deptOptions.join('') );

                        partOptions[partOptions.length] = '<option value=""></option>'
                        selectPart.empty().append( partOptions.join('') );
                    }
                },
            });
        });
        $('#departmentId').change(function() {
            let departmentId = $('#departmentId').val();
            var selectPart = $('#participantId');
            $.ajax({
                type: 'POST',
                async:false,
                url: '{{ url("event/getDeptPart") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    departmentId: departmentId,
                },
                success: function(response) {
                    var partOptions = [];
                    if(response.length){
                        for( item in response[2] ) {
                            html = '<option value="' + response[2][item] + '">' + response[3][item] + '</option>';
                            partOptions[partOptions.length] = html;
                        }
                        selectPart.empty().append( partOptions.join('') );
                    } else {
                        partOptions[partOptions.length] = '<option value=""></option>'
                        selectPart.empty().append( partOptions.join('') );
                    }
                },
            });
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
