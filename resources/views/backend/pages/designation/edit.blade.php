@extends('backend.layouts.master')
@section('css_after')

    <link rel="stylesheet" href="{{asset('backend/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/dropzone/dist/min/dropzone.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/flatpickr/flatpickr.min.css')}}">

@endsection
@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('designation/designation') }}">Designations</a></li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Edit Designation</h3>
            </div>

            <form class="js-validation" id='form' action='{{ url('designation/' . $designation_info->id . '/update')}}' method="POST" onsubmit="return validate_name(event)">
                @csrf

                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push ml-10">
                            <div class="col-lg-12 col-xl-12">

                                <div class="form-group">
                                    <label for="val-username">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $designation_info->name }}" placeholder="Enter a name.." >
                                    <span id="error_name" class="m-2" style="color:red;  font-size: 14px;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="val-suggestions">Branches<span class="text-danger">*</span></label>
                                        <select class="js-select2 form-control" id="branches" name="branches[]" style="width: 100%;" data-placeholder="Choose branches for the designation.." required multiple>
                                            <option></option>
                                            @foreach ($branches as $branch)
                                                <option value='{{ $branch->id }}' @if($branch->selected == "yes") selected @endif> {{ $branch->name }} </option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Department <span class="text-danger">*</span></label>
                                        <select class="js-select2 form-control" id="department" name="department" style="width: 100%;" data-placeholder="Choose parent menu.." required>
                                            <option value="{{$designation_info->department_id}}" selected>{{$designation_info->department_name}}</option>
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-suggestions">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="What it is used for?">{{ $designation_info->description ?? "" }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- END Regular -->

                        <!-- Submit -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" class="btn btn-alt-primary">Update</button>
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
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>
    <!-- Page JS Plugins -->
    <script src="{{asset('backend/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- Page JS Code -->
    <script>jQuery(function(){One.helpers(['select2']);});</script>
    <script type="text/javascript">
        $(document).ready(function() {
            fetch_departments();
        });
        $('#branches').on('change', function() {
            fetch_departments();
        });
        function fetch_departments() {
            var selectedBranches = $('#branches').val();
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
        }
        function validate_name(e) {
            var name = $('#name').val();
            $.ajax({
                type: 'POST',
                async:false,
                url: '{{ url('designation/'. $designation_info->id .'/validate_designation_name') }}',
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
                    }
                    return true;
                },
                error: function() {
                    e.preventDefault();
                    return false;
                }
            });

        }
    </script>

@endsection
