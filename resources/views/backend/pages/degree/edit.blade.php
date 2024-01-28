@extends('backend.layouts.master')

@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('degree/degree') }}">Degrees</a></li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
    @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Edit Degree</h3>
            </div>
            <form class="js-validation" id='form' action='{{ url('degree/' . $degree_info->id . '/update')}}' method="POST" onsubmit="return validate_name(event)">
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push ">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-username">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $degree_info->name }}" placeholder="Enter a name.." >
                                    <span id="error_name" class="m-2" style="color:red;  font-size: 14px;"></span>

                                </div>
                                <div class="form-group">
                                    <label for="val-suggestions">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="What it is used for?">{{ $degree_info->description ?? "" }}</textarea>
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
        function validate_name(e) {
            $.ajax({
                type: 'POST',
                async:false,
                url: '{{ url('degree/'. $degree_info->id .'/validate_name') }}',
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
