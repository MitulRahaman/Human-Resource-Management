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
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('institute/institute') }}">Institutes</a></li>
            <li class="breadcrumb-item">Add</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Add Institutes</h3>
            </div>

            <form class="js-validation form-prevent-multiple-submission" action="{{ url('institute/store') }}" id="form" method="POST" onsubmit="return validate_inputs(event)">
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
                                    <label for="val-suggestions">Address</label>
                                    <textarea class="form-control input-prevent-multiple-submission" id="address" name="address" rows="5" placeholder="Institute Address">{{ old('address') }}</textarea>
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
                    url: '{{ url('institute/validate_inputs') }}',
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
