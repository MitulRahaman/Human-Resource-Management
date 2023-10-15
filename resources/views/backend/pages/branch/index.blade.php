@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
@endsection
@section('page_action')
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('branch/add') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Add Branch
            </button>
        </a>
    </div>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">{{ $sub_menu }}</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <a class="block block-rounded block-link-pop" href="#">
                        <div class="block-content block-content-full text-center bg-gray-dark">
                            <div class="item item-2x item-circle bg-white-10 py-3 my-3 mx-auto invisible" data-toggle="appear" data-offset="50" data-class="animated fadeIn">
                                <i class="fas fa-building fa-2x text-white-75"></i>
                            </div>
{{--                            <div class="font-size-sm text-white-75">--}}
{{--                                <em>12 lessons</em> &bull; <em>5 hours</em>--}}
{{--                            </div>--}}
                        </div>
                        <div class="block-content block-content-full">
                            <h4 class="h5 mb-1">Branch Name</h4>
                            <div class="font-size-sm text-muted">Address</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
@endsection

@section('js_after')

    <!-- Page JS Code -->

    <script type="text/javascript">
        jQuery(document).ready(function ($) {

        });
    </script>
@endsection
