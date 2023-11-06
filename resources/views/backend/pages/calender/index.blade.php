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
                            <div class="col-12">
                                <!-- Calendar Container -->
                                <div id="calendar"></div>
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

    <script>
        window.onload = function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '{{url ('calender/get_events')}}',
                eventColor: '#A70000',
            });
            calendar.render();
        };

    </script>
@endsection
