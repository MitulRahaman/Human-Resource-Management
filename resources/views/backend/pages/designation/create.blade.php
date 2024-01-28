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
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('designation/designation') }}">Designations</a></li>
            <li class="breadcrumb-item">Add</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Add Designations</h3>
            </div>

            <form class="js-validation form-prevent-multiple-submission" action="{{ url('designation/store') }}" id="form" method="POST" onsubmit="return validate_inputs(event)">
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push ">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-username">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-prevent-multiple-submission" id="name" name="name" value="{{ old('name') }}"  placeholder="Enter a name.." required>
                                    <span id="error_name" class="m-2" style="color:red;  font-size: 14px;"></span>
                                </div>
                                <div class="form-group">
                                    <label for="val-suggestions">Branches<span class="text-danger">*</span></label>
                                        <select class="js-select2 form-control input-prevent-multiple-submission" id="branches" name="branches[]" style="width: 100%;" data-placeholder="Choose branches for the designation.." required multiple>
                                            <option></option>
                                            @foreach ($branches as $branch)
                                                <option value='{{ $branch->id }}'> {{ $branch->name }} </option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Department <span class="text-danger">*</span></label>
                                        <select class="js-select2 form-control input-prevent-multiple-submission" id="department" name="department" style="width: 100%;" data-placeholder="Choose parent menu.." required>
                                            <option></option>
                                        </select>
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
        function validate_inputs(e) {
            var name = $('#name').val();
            if ( name) {
                $.ajax({
                    type: 'POST',
                    async:false,
                    url: '{{ url('designation/validate_designation_inputs') }}',
                    data: $('#form').serialize(),
                    success: function (response) {
                        var name_msg = response.name_msg;
                        var success = response.success;
                        if (!success) {
                            if (name_msg) {
                                document.getElementById('error_name').innerHTML = name_msg;
                            }
                            else {
                                document.getElementById('error_name').innerHTML = '';
                            }
                            e.preventDefault();
                            return false;
                        } else {
                            return true;
                        }
                    },
                    error: function() {
                        e.preventDefault();
                        return false;
                    }
                });
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#branches').on('change', function() {
                var selectedBranches = $(this).val();
                $.ajax({
                    url: '{{ url('designation/fetch_departments') }}',
                    type: 'POST',
                    data: {
                        branches: selectedBranches,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $('#department').empty();
                        for (var i = 0; i < data.length; i++)
                        {
                            $('#department').append(
                                '<option value="' + data[i]['id'] + '" style="color:black">' + data[i]['name'] + '</option>'
                            );
                        }
                    }
                });
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
