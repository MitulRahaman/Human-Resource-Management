@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{asset('backend/js/plugins/fullcalendar/main.min.css')}}">
    <style >
        .fc-daygrid-day {
            background-color: #A3E2A3;
        }
    </style>
@endsection
@section('page_action')
    @if($hasManageCalenderPermission)
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('calender/manage') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="far fa-calendar-alt mr-1"></i> Manage Calender
            </button>
        </a>
        <a href="{{ url('calender/upload') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="far fa-calendar-plus mr-1"></i> Bulk Upload
            </button>
        </a>
    </div>
    @endif
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
        <div id="eventModal" class="modal fade" style="top: 30%;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editEvent" action="{{ url('calender/update_event')}}" method="post">
                        @csrf
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title text-center">Edit Event</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-fw fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content font-size-sm">
                                <p class="my-2">Title</p>
                                <input type="text" name="title" id="title" value="" style="width: 100%;" required>
                                <p class="my-2 ">Description</p>
                                <input type="text" name="description" id="description" value="" style="width: 100%;" required>
                                <input type="hidden" name="date" id="date">
                            </div>
                            <div class="block-content block-content-full text-right border-top">
                                <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="dayModal" class="modal fade" style="top: 30%;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="addEvent" action="{{ url('calender/add_event')}}" method="post">
                        @csrf
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title text-center">Add Event</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-fw fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content font-size-sm">
                                <p class="my-2">Enter Event Title</p>
                                <input type="text" name="title" id="title" style="width: 100%;" required><br>
                                <p class="my-2 ">Enter Event Description</p>
                                <input type="text" name="description" id="description" style="width: 100%;" >
                                <input type="hidden" name="day" id="day">
                            </div>
                            <div class="block-content block-content-full text-right border-top">
                                <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- END Dynamic Table with Export Buttons -->
    </div>
@endsection

@section('js_after')
    <!-- Page JS Plugins -->

    <script src="{{asset('backend/js/plugins/fullcalendar/main.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    <script>
        window.onload = function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '{{url ('calender/get_events')}}',
                eventColor: '#A70000',
                editable: true,
                eventClick:  function(event) {
                    var date = event.event.start;
                    var title = event.event._def.title;
                    var description = event.event.extendedProps.description;
                    date = moment(date).format('YYYY-MM-DD');
                    $('#date').val(date);
                    document.getElementById('title').setAttribute('value',title);
                    document.getElementById('description').setAttribute('value',description);
                    $('#eventModal').modal();
                },
                dateClick : function(date) {
                    date = moment(date.date).format('YYYY-MM-DD');
                    $('#day').val(date);
                    $('#dayModal').modal();
                },
                eventDrop: function (event) {
                    var title = event.event._def.title;
                    var newDate= event.event.start;
                    newDate = moment(newDate).format('YYYY-MM-DD');
                    var oldDate = event.oldEvent.start;
                    oldDate = moment(oldDate).format('YYYY-MM-DD');
                    var data={
                        'title': title,
                        'newDate': newDate,
                        'oldDate': oldDate,
                    }
                    $.ajax({
                        type: 'GET',
                        url: '{{ url('calender/save_event') }}',
                        async: false,
                        data: data,
                        success: function (response) {
                            One.helpers('notify', {type: 'success', icon: 'fa fa-check mr-1', message: 'Event updated!'});
                        },
                        error: function() {
                            One.helpers('notify', {type: 'danger', icon: 'fa fa-check mr-1', message: 'Event not updated!'});
                        }
                    });
                },
                eventDidMount: function (event) {
                    if (event.el) {
                        event.el.setAttribute('data-toggle', 'tooltip');
                        event.el.setAttribute('data-placement', 'top');
                        event.el.setAttribute('title', event.event._def.title);
                        $(event.el).tooltip();
                    }
                },
            });
            calendar.render();
        };

    </script>
    <script src="{{asset('backend/js/plugins/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
    <script>jQuery(function(){One.helpers('notify');});</script>
@endsection
