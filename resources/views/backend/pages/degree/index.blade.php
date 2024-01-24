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
    @if ($addDegreePermission)
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('degree/add') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Add Degree
            </button>
        </a>
    </div>
    @endif
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Manage Degrees</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons " id="degree_table">
                            <thead>
                            <tr>
                                <th class="text-center ">#</th>
                                <th >Name</th>
                                <th class="d-none d-sm-table-cell " style="width: 20%;">Description</th>
                                <th >Deleted</th>
                                <th >Created At</th>
                                @if ($hasDegreeManagePermission)
                                <th >Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                </table>
                </div>
                <!-- Vertically Centered Block Modal -->
                <div class="modal" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form id="delete" action="" method="post">
                                @csrf
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title text-center">Delete Degree</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        <p class="text-center"><span id="degree_name"></span> Degree will be deleted. Are you sure?</p>
                                        <input type="hidden" name="delete_degree_id" id="delete_degree_id">
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
                <div class="modal" id="restore-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form id="restore" action="" method="post">
                                @csrf
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title text-center">Restore Degree</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        <p class="text-center"><span id="restore_degree_name"></span> Degree will be restored. Are you sure?</p>
                                        <input type="hidden" name="restore_degree_id" id="restore_degree_id">
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
            $('#degree_table').DataTable().destroy();

            var dtable = $('#degree_table').DataTable({
                responsive: true,
                ajax: '{{ url('degree/get_degree_data') }}',
                paging: true,
                dom: 'B<"top"<"left-col"l><"right-col"f>>rtip',
                retrieve: true,
                "order": [[ 0, "asc" ]],
                buttons : [{
                    extend: 'copy',
                    text: 'Copy',
                    className: 'button',
                    title: "Degree Table"
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    className: 'button' ,
                    exportOptions:  {
                                        columns: [0, 1,2,3]
                                    },
                    title: "Degree Table"
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'button' ,
                    exportOptions:  {
                                        columns: [0, 1,2,3]
                                    },
                    title: "Degree Table"
                },
            ],
            lengthMenu: [[ 10, 25, 50, -1], [ 10, 25, 50, 'All']],
            });
            dtable.buttons().container().addClass('center-align-buttons');
        });
        function show_delete_modal(id, name) {
            var x = document.getElementById('degree_name');
            x.innerHTML = name;
            const url = "{{ url('degree/:id/delete') }}".replace(':id', id);
            $('#delete').attr('action', url);
            $('#delete-modal').modal('show');
        }
        function show_restore_modal(id, name) {
            var x = document.getElementById('restore_degree_name');
            x.innerHTML = name;
            const url = "{{ url('degree/:id/restore') }}".replace(':id', id);
            $('#restore').attr('action', url);
            $('#restore-modal').modal('show');
        }
    </script>
@endsection
