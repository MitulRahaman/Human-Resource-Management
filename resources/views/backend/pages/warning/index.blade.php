@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <style >
        .button {
            border-radius: 10px;
        }
        .button:hover {
            background-color: #999999;
            color: #fff;
        }
        .center-align-buttons {
            text-align: center;
        }
        .left-col {
            float: left;
            width: 50%;
        }
        .center-col {
            float: left;
            width: 50%;
        }
        .right-col {
            float: left;
            width: 50%;
        }

    </style>
@endsection
@section('page_action')
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('warning/add') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Add Warning
            </button>
        </a>
    </div>
@endsection
@section('content')

    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Manage Warnings</h3>
            </div>
            <div class="block-content block-content-full">

                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons " id="table">

                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Subject</th>
                            <th class="text-center">Warning To</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Created At</th>
                            <th >Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>

                <!-- Vertically Centered Block Modal -->

                <!-- END Vertically Centered Block Modal -->
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
@endsection

@section('js_after')
    <script src="{{ asset('backend/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_tables_datatables.min.js') }}"></script>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#table').DataTable().destroy();
            var dtable = $('#table').DataTable({
                responsive: true,
                ajax: '{{ url('warning/get_warning_data') }}',
                paging: true,
                dom: 'B<"top"<"left-col"l><"right-col"f>>rtip',
                retrieve: true,
                "order": [[ 0, "asc" ]],
                buttons : [{
                    extend: 'copy',
                    text: 'Copy',
                    className: 'button',
                    title: "Warning Table"
                },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'button' ,
                        exportOptions:  {
                            columns: [0, 1,2,3,4,5,6]
                        },
                        title: "Warning Table"

                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'button' ,
                        exportOptions:  {
                            columns: [0, 1,2,3,4,5,6]
                        },
                        title: "Warning Table"
                    },
                ],
                lengthMenu: [[ 10, 25, 50, -1], [ 10, 25, 50, 'All']],
            });
            dtable.buttons().container().addClass('center-align-buttons');
        });
        

    </script>
@endsection
