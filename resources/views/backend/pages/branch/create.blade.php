@extends('backend.layouts.master')
@section('css_after')
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
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('branch') }}">Branches</a></li>
            <li class="breadcrumb-item">Add</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
    @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Add Branch</h3>
            </div>

            <!-- jQuery Validation (.js-validation class is initialized in js/pages/be_forms_validation.min.js which was auto compiled from _js/pages/be_forms_validation.js) -->
            <form class="js-validation form-prevent-multiple-submission" action="{{ url('branch/store') }}" method="POST" onsubmit="return verify_inputs()" id="form">
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-title">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-prevent-multiple-submission" id="name" name="name" value="{{ old('name') }}" placeholder="Enter a name..">
                                    <span id="error_name" style="font-size:13px; color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="val-description">Address</label>
                                    <textarea class="form-control input-prevent-multiple-submission" id="address" name="address" rows="3" placeholder="Address?">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Save -->
                        <div class="row items-push">
                            <div class="col-lg-5 offset-lg-5">
                                <button type="submit" class="btn btn-alt-primary button-prevent-multiple-submission" id="submit">
                                    <i class="spinner fa fa-spinner fa-spin"></i>Save
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
    <script>
        function verify_inputs(e){
            let _name = $('#name').val();
            let flag = 0;
            $.ajax({
                type: 'POST',
                async:false,
                url: '{{ route("verifydata") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: _name
                },
                context: this,
                success: function(response) {
                    if (!response.success) {
                        document.getElementById('error_name').innerHTML = response.name_msg;
                        flag = 0;
                    }
                    else{
                        flag = 1;
                    }
                },
            });
            if(!flag)
                return false;
            else{
                $('#submit').attr('disabled', true);
                return true;
            }
        }
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
