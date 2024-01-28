@extends('backend.layouts.master')
@section('css_after')

    <link rel="stylesheet" href="{{asset('backend/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/dropzone/dist/min/dropzone.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/flatpickr/flatpickr.min.css')}}">
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
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('meeting/') }}">Meetings</a></li>
            <li class="breadcrumb-item">Add</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Add Meetings</h3>
            </div>

            <form class="js-validation form-prevent-multiple-submission" action="{{ url('meeting/store') }}" id="form" method="POST">
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push ">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-username">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-prevent-multiple-submission" id="title" name="title" value="{{ old('title') }}"  placeholder="Enter a title.." required>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Agenda <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-prevent-multiple-submission" id="agenda" name="agenda" value="{{ old('agenda') }}"  placeholder="Enter a name.." required>
                                </div>
                                <div class="form-group ">
                                    <label for="example-flatpickr-default">Date<span class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control bg-white " data-date-format="d-m-Y" id="date" name="date" placeholder="d-m-Y" value="{{ old('date')}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="val-suggestions">Place<span class="text-danger">*</span></label>
                                    <select class="js-select2 form-control input-prevent-multiple-submission" id="place" name="place" style="width: 100%;" data-placeholder="Choose place for the meeting.." required>
                                        <option></option>
                                        @foreach ($places as $place)
                                            <option value='{{ $place->id }}'> {{ $place->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="example-flatpickr-time-standalone">Start Time(24 H:i)<span class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control bg-white" id="start_time" name="start_time" data-enable-time="true" data-no-calendar="true" data-date-format="H:i" data-placeholder="Choose Hour minute in 24 hour format.." data-time_24hr="true" required>
                                </div>
                                <div class="form-group">
                                    <label for="example-flatpickr-time-standalone">End Time(24 H:i)<span class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control bg-white" id="end_time" name="end_time" data-enable-time="true" data-no-calendar="true" data-date-format="H:i" data-placeholder="Choose Hour minute in 24 hour format.." data-time_24hr="true" required>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Url</label>
                                    <input type="text" class="form-control input-prevent-multiple-submission" id="url" name="url" value="{{ old('url') }}"  placeholder="Enter a url in case of virtual meeting..">
                                </div>
                                <div class="form-group">
                                    <label for="val-suggestions">Participants<span class="text-danger">*</span></label>
                                    <select class="js-select2 form-control input-prevent-multiple-submission" id="participants" name="participants[]" style="width: 100%;" data-placeholder="Choose participants for the meeting.." multiple required>
                                        <option value="all">Select All</option>
                                        <option value="unSelectAll">Unselect All</option>
                                        @foreach ($participants as $participant)
                                            <option value='{{ $participant->id }}'> {{ $participant->full_name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-suggestions">Description<span class="text-danger">*</span></label>
                                    <textarea class="form-control input-prevent-multiple-submission" id="description" name="description" rows="5" placeholder="What it is used for?" required>{{ old('description') }}</textarea>
                                </div>
                                <input type="hidden" name="timezone" value="" id="timezone">
                            </div>
                        </div>

                        <!-- END Regular -->

                        <!-- Submit -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" class="btn btn-alt-primary button-prevent-multiple-submission" id="submit">
                                    <i class="spinner fa fa-spinner fa-spin"></i>Save
                                </button>
                            </div>
                        </div>
                        <!-- END Submit -->
                    </div>
                </div>
            </form>
            <!-- jQuery Validation -->
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
@endsection

@section('js_after')

    <script src="{{ asset('backend/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/jquery-validation/additional-methods.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('#participants').change(function() {
                if ($(this).val() && $(this).val().includes('all')) {
                    $(this).find('option[value="all"]').prop('selected', false);
                    $(this).find('option[value!="all"]').prop('selected', true);
                    $(this).find('option[value="unSelectAll"]').prop('selected', false);
                }else if($(this).val() && $(this).val().includes('unSelectAll')){
                    $(this).find('option[value!=""]').prop('selected', false);
                }
                else {
                    $(this).find('option[value="all"]').prop('selected', false);
                }
                $(this).trigger('change.select2');
            });
            $('.js-select2').select2({
                placeholder: 'Choose Permissions for the Role..',
            });
            var timezone = document.getElementById("timezone");
            timezone.setAttribute('value', Intl.DateTimeFormat().resolvedOptions().timeZone);
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

    <!-- Page JS Plugins -->

    <script src="{{asset('backend/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/dropzone/dropzone.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
    <script>jQuery(function(){One.helpers(['flatpickr', 'datepicker', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);});</script>

@endsection
