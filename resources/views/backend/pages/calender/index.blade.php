@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{asset('backend/js/plugins/fullcalendar/main.min.css')}}">
@endsection
@section('page_action')
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('calender/manage') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Manage Calender
            </button>
        </a>
    </div>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')

                <div class="block block-rounded">
                    <div class="block-content">
                        <div class="row items-push">
                            <div class="col-md-8 col-lg-7 col-xl-9">
                                <!-- Calendar Container -->
                                <div id="js-calendar"></div>
                            </div>
                            <div class="col-md-4 col-lg-5 col-xl-3">
                                <!-- Add Event Form -->
                                <form class="js-form-add-event push">
                                    <div class="input-group">
                                        <input type="text" class="js-add-event form-control" placeholder="Add Event..">
                                        <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-fw fa-plus-circle"></i>
                                                </span>
                                        </div>
                                    </div>
                                </form>
                                <!-- END Add Event Form -->

                                <!-- Event List -->
                                <ul id="js-events" class="list list-events">
                                    <li>
                                        <div class="js-event p-2 text-white font-size-sm font-w500 bg-info">Codename X</div>
                                    </li>
                                    <li>
                                        <div class="js-event p-2 text-white font-size-sm font-w500 bg-success">Weekend Adventure</div>
                                    </li>
                                    <li>
                                        <div class="js-event p-2 text-white font-size-sm font-w500 bg-info">Project Mars</div>
                                    </li>
                                    <li>
                                        <div class="js-event p-2 text-white font-size-sm font-w500 bg-warning">Meeting</div>
                                    </li>
                                    <li>
                                        <div class="js-event p-2 text-white font-size-sm font-w500 bg-success">Walk the dog</div>
                                    </li>
                                    <li>
                                        <div class="js-event p-2 text-white font-size-sm font-w500 bg-info">AI schedule</div>
                                    </li>
                                    <li>
                                        <div class="js-event p-2 text-white font-size-sm font-w500 bg-success">Cinema</div>
                                    </li>
                                    <li>
                                        <div class="js-event p-2 text-white font-size-sm font-w500 bg-danger">Project X</div>
                                    </li>
                                    <li>
                                        <div class="js-event p-2 text-white font-size-sm font-w500 bg-warning">Skype Meeting</div>
                                    </li>
                                </ul>
                                <div class="text-center">
                                    <p class="font-size-sm text-muted">
                                        <i class="fa fa-arrows-alt"></i> Drag and drop events on the calendar
                                    </p>
                                </div>
                                <!-- END Event List -->
                            </div>
                        </div>
                    </div>
                </div>

        <!-- END Dynamic Table with Export Buttons -->
    </div>
@endsection

@section('js_after')
    <!-- Page JS Plugins -->

    <script src="{{asset('backend/js/plugins/fullcalendar/main.min.js')}}"></script>
    <script src="{{asset('backend/js/oneui.core.min.js')}}"></script>
    <script src="{{asset('backend/js/oneui.app.min.js')}}"></script>

    <!-- Page JS Code -->
    <script src="{{asset('backend/js/pages/be_comp_calendar.min.js')}}"></script>
@endsection
