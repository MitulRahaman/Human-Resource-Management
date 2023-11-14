@extends('backend.layouts.master')

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

            <form class="js-validation" action="{{ url('calender/store') }}" id="form" method="POST" >
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push ">
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group">
                                    <label for="val-username">Month</label>
                                    <div class="form-group">
                                        <select class="js-select2 form-control" id="month" name="month" onchange="loadDates()" style="width: 100%;" data-placeholder="Choose month..">
                                                <option value='1' style="color:black" selected> January </option>
                                                <option value='2' style="color:black"> February </option>
                                                <option value='3' style="color:black"> March </option>
                                                <option value='4' style="color:black"> April </option>
                                                <option value='5' style="color:black"> May </option>
                                                <option value='6' style="color:black"> June </option>
                                                <option value='7' style="color:black"> July </option>
                                                <option value='8' style="color:black"> August </option>
                                                <option value='9' style="color:black"> September </option>
                                                <option value='10' style="color:black"> October </option>
                                                <option value='11' style="color:black"> November </option>
                                                <option value='12' style="color:black"> December </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group">
                                    <label for="val-username">Year</label>
                                    <div class="form-group">
                                        <select class="js-select2 form-control" id="year" name="year" onchange="loadDates()" style="width: 100%;" data-placeholder="Choose year..">
                                            <option value='2023' style="color:black" selected> 2023 </option>
                                            <option value='2024' style="color:black"> 2024 </option>
                                            <option value='2025' style="color:black"> 2025 </option>
                                            <option value='2026' style="color:black"> 2026 </option>
                                            <option value='2027' style="color:black"> 2027 </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- END Regular -->
                        <div id="date-output"></div>
                        <!-- Submit -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4 m-2 center">
                                <button type="submit" class="btn btn-alt-primary" id="submit" >Submit</button>
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
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>
    <script>
            function loadDates() {
                var selectedYear = $('#year').val();
                var selectedMonth = $('#month').val();
                if (selectedYear && selectedMonth) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ url('calender/get_dates') }}',
                        async: false,
                        data: {
                            year: selectedYear,
                            month: selectedMonth,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            var dates = response['dates'];
                            var calender = response['calender'];
                            $('#date-output').empty();
                            $('#date-output').append('<div class="row m-2" > <div class="col-2">Date</div><div class="col-2">Day</div><div class="col-3"></div><div class="col-2">Title</div><div class="col-3">Description</div><br>');
                            for (var i = 0; i < dates.length; i++) {
                                var date = new Date(dates[i]);
                                var dayOfWeek = date.getDay();
                                var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                                var dayName = dayNames[dayOfWeek];
                                var exists = calender.some(obj => obj.hasOwnProperty('date') && obj['date'] === dates[i]);
                                if(exists)
                                {
                                    var title = calender.filter(obj => obj.date === dates[i]).map(filteredObj => filteredObj.title);
                                    var data = calender.find(obj => obj.date === dates[i]);
                                    console.log(data.description);
                                    $('#date-output').append('<div class="row m-2" id="div_'+i+'" > ' +
                                        '<div class="col-2">'  +dates[i] + '</div>' +
                                        '<input type="hidden" class="form-control" id="date" name="date[]" value="' +dates[i] + '">'+
                                        '<div class="col-2"> ' + dayName + '</div>' +
                                        '<div class="col-3"> ' +
                                        '<select class="js-select2 form-control" id="day" name="day[]"  >'+
                                        '<option value="0" style="color:black"  > working day </option>'+
                                        '<option value="1" style="color:black" selected > off day </option>'+
                                        ' </select>'+
                                        '</div>'+
                                        '<div class="col-2"> ' +
                                        ' <input type="text" class="form-control" id="title" name="title[]" value="'+data.title+'">'+
                                        '</div>'+
                                        '<div class="col-3"> ' +
                                        ' <input type="text" class="form-control" id="description" name="description[]"  value="'+data.description+'">'+
                                        '</div>'+
                                        '<br>');
                                }
                                else{
                                    $('#date-output').append('<div class="row m-2" id="div_'+i+'" > ' +
                                        '<div class="col-2">'  +dates[i] + '</div>' +
                                        '<input type="hidden" class="form-control" id="date" name="date[]" value="' +dates[i] + '">'+
                                        '<div class="col-2"> ' + dayName + '</div>' +
                                        '<div class="col-3"> ' +
                                        '<select class="js-select2 form-control" id="day" name="day[]"  >'+
                                        '<option value="0" style="color:black" selected > working day </option>'+
                                        '<option value="1" style="color:black" > off day </option>'+
                                        ' </select>'+
                                        '</div>'+
                                        '<div class="col-2"> ' +
                                        ' <input type="text" class="form-control" id="title" name="title[]"  >'+
                                        '</div>'+
                                        '<div class="col-3"> ' +
                                        ' <input type="text" class="form-control" id="description" name="description[]"  >'+
                                        '</div>'+
                                        '<br>');
                                }

                            }
                        },
                        error: function (xhr, status, error) {
                            console.log(error);
                        }
                    });
                }
            }
    </script>
@endsection
