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
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('role/role') }}">Roles</a></li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Edit Role</h3>
            </div>

            <form class="js-validation" id='form' action='{{ url('role/' . $role_info->id . '/update')}}' method="POST" onsubmit="return validate_name(event)">
                @csrf

                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push ">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-username">sl_no <span class="text-danger">*</span></label>
                                    <input type="number" step="1" class="form-control" id="sl_no" name="sl_no" value="{{ $role_info->sl_no }}" placeholder="Enter a serial number.." required>
                                    <span id="error_sl_no"  class="m-2" style="color:red;  font-size: 14px;"></span>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $role_info->name }}" placeholder="Enter a name.." >
                                    <span id="error_name" class="m-2" style="color:red;  font-size: 14px;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="val-suggestions">Permissions</label>
                                        <select class="js-select2 form-control" id="permissions" name="permissions[]" style="width: 100%;" data-placeholder="Choose Permissions for the Role.." multiple>
                                            <option value="all">Select All</option>
                                            <option value="unSelectAll">Unselect All</option>
                                            @foreach ($permissions as $permission)
                                                <option value='{{ $permission->id }}' @if($permission->selected == "yes") selected @endif> {{ $permission->name }} </option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-suggestions">Branches<span class="text-danger">*</span></label>
                                        <select class="js-select2 form-control" id="branches" name="branches[]" style="width: 100%;" data-placeholder="Choose Permissions for the Role.." required multiple>
                                            <option></option>
                                            @foreach ($branches as $branch)
                                                <option value='{{ $branch->id }}' @if($branch->selected == "yes") selected @endif> {{ $branch->name }} </option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-suggestions">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="What it is used for?">{{ $role_info->description ?? "" }}</textarea>
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

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#permissions').change(function() {
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
        });
    </script>
    <script>
        function validate_name(e) {
            var name = $('#name').val();
            $.ajax({
                type: 'POST',
                async:false,
                url: '{{ url('role/'. $role_info->id .'/validate_role_name') }}',
                data: $('#form').serialize(),
                success: function (response) {
                    var data = $.parseJSON(response);
                    var name_msg = data.name_msg;
                    var success = data.success;
                    if (!success) {
                        if (name_msg) {
                            document.getElementById('error_name').innerHTML = name_msg;
                        } else {
                            document.getElementById('error_name').innerHTML = '';
                        }
                        document.getElementById('error_name').innerHTML = '';
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
