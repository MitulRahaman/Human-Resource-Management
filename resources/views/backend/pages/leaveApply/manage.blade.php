@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.colVis.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.colVis2.css') }}">

@endsection
@section('page_action')
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('leaveApply/apply') }}">  
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Apply for Leave
            </button>
        </a>
    </div>
@endsection
@section('content')
    <div class="content"> 
        <div class="block block-rounded">
        @include('backend.layouts.error_msg')
            <div class="block-header">
                <h3 class="block-title mt-4">{{ $sub_menu }}</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive"> 
                    <table class="table table-bordered table-striped table-vcenter" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-center">Sl no.</th>
                                <th class="text-center">Employee ID</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Employee Phone</th>
                                <th class="text-center">Subject</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">End date</th>
                                <th class="text-center">Days</th>
                                <th class="text-center">Reason</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_after') 

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.colVis.js') }}"></script>

    <script>
        //create table
        jQuery(function(){
            function createTable(){
                $('#dataTable').DataTable( {
                    dom: 'Bfrtip',
                    ajax: {
                        type: 'POST',
                        url: '{{ url("leaveApply/get_table_data") }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                        }
                    },
                    buttons: [
                        {
                            extend: 'copy',
                            text: 'Copy',
                            title: "Leave Table"
                        },
                        {
                            extend: 'csv',
                            text: 'CSV',
                            title: "Leave Table"
                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible'
                            },
                            title: "Leave Table"
                        },
                        'colvis'
                    ],
                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'All']],
                });
            }
            createTable(); 
        });
        //end create table

     </script>

    <script src="{{ asset('backend/_js/pages/be_tables_datatables.js') }}"></script>


@endsection
