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
        <div class="block block-rounded">
            <div class="block-content">
                <div class="row items-push">
                    <div class="col-12">
                        <div id="calendar">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_after')

    <!-- Page JS Code -->
    <script src="{{asset('backend/js/plugins/fullcalendar/main.min.js')}}"></script>

    <script>
        window.onload = function() {
            var _events = @json($events);
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                eventColor: '#389936',
                eventTextColor : '#071307',
                events: _events,
                eventClick:  function(event) {
                    var id = event.event._def.publicId;
                    window.location.href =  "{{ url('event/:id/edit') }}".replace(':id', id);
                },
            });
            calendar.render();
        };

    </script>
    <script src="{{asset('backend/js/plugins/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
    <script>jQuery(function(){One.helpers('notify');});</script>

@endsection
