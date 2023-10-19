@extends('backend.layouts.master')

@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('permission/permission') }}">Permissions</a></li>
            <li class="breadcrumb-item">Add</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
    @include('backend.layouts.error_msg')
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Add Permissions</h3>
            </div>

            <!-- jQuery Validation (.js-validation class is initialized in js/pages/be_forms_validation.min.js which was auto compiled from _js/pages/be_forms_validation.js) -->
            <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
            <form class="js-validation" action="{{ url('permission/store') }}" id="form" method="POST" onsubmit="return validate_inputs(event)">
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push ml-10">
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group">
                                    <label for="val-username">Slug <span class="text-danger">*</span></label>
                                    <input type="text"  class="form-control" id="slug" name="slug" value="{{ old('slug') }}" placeholder="..." >
                                    <span id="error_slug"  class="m-2" style="color:red;  font-size: 14px;"></span>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"  placeholder="Enter a name.." >
                                    <span id="error_name" class="m-2" style="color:red;  font-size: 14px;"></span>

                                </div>
                                <div class="form-group">
                                    <label for="val-suggestions">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="What it is used for?">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- END Regular -->

                        <!-- Submit -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" class="btn btn-alt-primary" id="create" >Submit</button>
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
           //
            var slug = $('#slug').val();
            var name = $('#name').val();
//alert('asdasdasd');

            $.ajax({
                type: 'POST',
                async:false,
                url: '{{ url('permission/validate_inputs') }}',
                data: $('#form').serialize(),

                success: function (response) {

                    var slug_msg = response.slug_msg;
                    var name_msg = response.name_msg;
                    var success = response.success;

                        if (!success) {


                            if (slug_msg) {
                                document.getElementById('error_slug').innerHTML = slug_msg;

                            }
                            else {
                                document.getElementById('error_slug').innerHTML = '';
                            }
                            if (name_msg) {
                                document.getElementById('error_name').innerHTML = name_msg;
                            }
                            else {
                                document.getElementById('error_name').innerHTML = '';
                            }
                            e.preventDefault();
                            return false;

                        }
                        $('#create').attr('disabled', true);
                        return true;



                },
                error: function() {
                    return false;
                }
            });

        }
    </script>



@endsection
