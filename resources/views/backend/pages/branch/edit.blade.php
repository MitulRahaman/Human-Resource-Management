@extends('backend.layouts.master')
@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('branch') }}">Branches</a></li>
            <li class="breadcrumb-item">Update</li>
        </ol>
    </nav>
@endsection
@section('content')

    <div class="content">
            @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Update Branch</h3>
            </div>
            <!-- jQuery Validation (.js-validation class is initialized in js/pages/be_forms_validation.min.js which was auto compiled from _js/pages/be_forms_validation.js) -->
            <form class="js-validation" action="{{ route('branch.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-8 col-xl-5">
                                <div class="form-group">
                                    <label for="val-title">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $data->name }}"  required>
                                    <span id="name_error" style="color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="val-description">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" rows="3" value="{{ $data->address }}"  required>
                                </div>
                            </div>
                        </div>
                        <!-- END Regular -->

                        <!-- Update -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" class="btn btn-alt-primary">Update</button>
                            </div>
                        </div>
                        <!-- END Update -->
                    </div>
                </div>
            </form>
            <!-- jQuery Validation -->
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
@endsection

@section('js_after')

    <script> 
        $(document).ready(function(){
            $('#name').on('focusout', function(){
                var _name = $('#name').val();
                var _url = "{{ url('branch/verifydata') }}";
                $.ajax({                   
                    type: "post",
                    url: _url,
                    data:{
                        _token: '{{ csrf_token() }}',
                        name:_name,
                        },
                    success: function(){
                        $("#name_error").fadeIn().html(" ");
                    },
                    error:function(e) {
                        $("#name_error").fadeIn().html(e.responseJSON.errors);
                    }
                });
            });
        });
    </script>

    <script src="{{ asset('backend/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/jquery-validation/additional-methods.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>
@endsection
