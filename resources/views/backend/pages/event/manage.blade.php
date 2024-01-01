@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/fullcalendar/main.min.css') }}">
@endsection
@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('event/manage') }}">Events</a></li>
            <li class="breadcrumb-item">Manage</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
        <!-- Calendar -->
        <div class="block block-rounded">
            <div class="block-content">
                <div class="row items-push">
                    <div class="col-md-8 col-lg-7 col-xl-9">
                        <div id="js-calendar"></div>
                    </div>
                    <div class="col-md-4 col-lg-5 col-xl-3">
                        <ul id="js-events" class="list list-events">
                            <li>
                                <div class="js-event p-2 text-white font-size-sm font-w500 bg-info">Codename X</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Calendar -->
    </div>
@endsection

@section('js_after')

    <!-- Page JS Plugins -->
    <script src="{{ asset('backend/js/plugins/fullcalendar/main.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_comp_calendar.min.js') }}"></script>

@endsection
