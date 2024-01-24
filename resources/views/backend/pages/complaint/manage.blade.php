@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.colVis.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.colVis2.css') }}">
    <style >
        div.dataTables_wrapper div.dataTables_length
        {
            margin-left: 20px;
            float: right;
        }
        div.dataTables_wrapper div.dataTables_length select
        {
            width: 50px;;
        }
        .dataTables_wrapper div.dataTables_scrollBody {
            min-height: 130px;
        }
    </style>
@endsection
@section('page_action')
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('complaint/create') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Create Complaint
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
                                <th class="text-center">Title</th>
                                <th class="text-center">By Whom</th>
                                <th class="text-center">Against Whom</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Complaint Date</th>
                                <th class="text-center">Remarks</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">File</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                 <!-- Vertically Centered Block Modal -->
                 <div class="modal" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form id="approve" action="" method="post">
                                @csrf
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title text-center">Acknowledge</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group font-size-sm px-6">
                                        <p class="text-center mt-2"><span id="approve_complaint"></span>Acknowledgement Note</p>
                                        <input class="form-control bg-light" type="text" name="approve-modal-remarks" id="approve-modal-remarks" style="width: 100%" value="">
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
                <div class="modal" id="reject-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form id="reject" action="" method="post">
                                @csrf
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title text-center">Reject</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group font-size-sm px-6">
                                        <p class="text-center mt-2"><span id="reject_complaint"></span> Rejection Note</p>
                                        <input class="form-control bg-light" type="text" name="reject-modal-remarks" id="reject-modal-remarks" value="" style="width: 100%">
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
                <div class="modal" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form id="delete" action="" method="post">
                                @csrf
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title text-center">Delete User</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        <p class="text-center">Complaint will be deleted. Are you sure?</p>
                                        <input type="hidden" name="delete_user_id" id="delete_user_id">
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
        jQuery(function(){
            function createTable(){
                $('#dataTable').DataTable( {

                    dom: 'Blfrtip',
                    ajax: {
                        type: 'POST',
                        url: '{{ url("complaint/get_table_data") }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                        }
                    },
                    buttons: [
                        {
                            extend: 'copy',
                            text: 'Copy',
                            title: "Complaint Table"
                        },
                        {
                            extend: 'csv',
                            text: 'CSV',
                            title: "Complaint Table"
                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible'
                            },
                            title: "Complaint Table"
                        },
                        'colvis'
                    ],
                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'All']],
                });
            }
            createTable();
        });
        function show_approve_modal(id) {
            const url = "{{ url('complaint/:id/approve') }}".replace(':id', id);
            $('#approve').attr('action', url);
            $('#approve-modal').modal('show');
        }
        function show_reject_modal(id) {
            const url = "{{ url('complaint/:id/reject') }}".replace(':id', id);
            $('#reject').attr('action', url);
            $('#reject-modal').modal('show');
        }
        function show_delete_modal(id) {
            const url = "{{ url('complaint/:id/delete') }}".replace(':id', id);
            $('#delete').attr('action', url);
            $('#delete-modal').modal('show');
        }
     </script>

    <script src="{{ asset('backend/_js/pages/be_tables_datatables.js') }}"></script>


@endsection
