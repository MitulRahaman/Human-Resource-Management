@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{asset('backend/js/plugins/dropzone/dist/min/dropzone.min.css')}}">
@endsection
@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('calender/calender') }}">Calender</a></li>
            <li class="breadcrumb-item">Add</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Manage Calender</h3>
            </div>
                    <form method="post" action="{{ url('calender/save_excel') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="block block-rounded">
                            <div class="block-content block-content-full">
                                <div class="row items-push ml-10">
                                    <div class="col-lg-6 col-xl-6">
                                        <div class="form-group">
                                            <label for="val-username">Only Excel File <span class="text-danger">*</span></label>
                                            <p> must have - Date (YYYY-MM-DD), Title, Description </p>
                                            <input type="file"  class="form-control" id="file" name="file" placeholder="..." required>

                                        </div>
                                    </div>
                                </div>
                                <!-- END Regular -->

                                <!-- Submit -->
                                <div class="row items-push">
                                    <div class="col-lg-7 offset-lg-4">
                                        <button type="submit" class="btn btn-alt-primary" id="submit" >Submit</button>
                                    </div>
                                </div>
                                <!-- END Submit -->
                            </div>
                        </div>
                    </form>

        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
@endsection

@section('js_after')

    <script src="{{ asset('backend/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/jquery-validation/additional-methods.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>
@endsection
