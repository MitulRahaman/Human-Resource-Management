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
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('ticket/') }}">Tickets</a></li>
            <li class="breadcrumb-item">Add</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Add Tickets</h3>
            </div>

            <form class="js-validation form-prevent-multiple-submission" action="{{ url('ticket/store') }}" id="form" method="POST" >
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push ">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-username">Subject <span class="text-danger">*</span></label>
                                    <input type="text"  class="form-control input-prevent-multiple-submission" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Enter a subject.." required>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Assigned To <span class="text-danger">*</span></label>
                                    <select class="js-select2 form-control input-prevent-multiple-submission" id="assigned_to" name="assigned_to" style="width: 100%;" value="{{ old('assigned_to') }}" data-placeholder="Choose user to assign the ticket.." required>
                                        <option></option>
                                        @foreach ($users as $user)
                                            <option value='{{ $user->id }}' style="color:black"> {{ $user->full_name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Priority<span class="text-danger">*</span></label>
                                    <select class="js-select2 form-control input-prevent-multiple-submission" id="priority" name="priority" value="{{ old('priority') }}" style="width: 100%;" data-placeholder="Choose priority of the ticket.." required>
                                        <option></option>
                                        <option value='{{ $priority['low']}}' style="color:black"> Low </option>
                                        <option value='{{ $priority['medium']}}' style="color:black"> Medium </option>
                                        <option value='{{ $priority['high']}}' style="color:black"> high </option>
                                        <option value='{{ $priority['critical']}}' style="color:black"> Critical </option>
                                    </select>
                                </div>
                                <div class="form-group ">
                                    <label for="example-flatpickr-default">Deadline<span class="text-danger">*</span></label>
                                    <input type="text" class="js-flatpickr form-control bg-white " data-date-format="d-m-Y" id="deadline" name="deadline" placeholder="d-m-Y" value="{{ old('deadline')}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="val-suggestions">Description</label>
                                    <textarea class="form-control input-prevent-multiple-submission" id="description" name="description" rows="5" placeholder="What it is used for?">{{ old('description') }}</textarea>
                                </div>
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
