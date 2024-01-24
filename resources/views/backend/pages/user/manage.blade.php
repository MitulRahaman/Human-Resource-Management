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
    @if ($hasUserManagePermission)
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('user/create') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Add User
            </button>
        </a>
    </div>
    @endif
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
                            <th class="text-center">Photo</th>
                            <th class="text-center">Employee ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone No.</th>
                            <th class="text-center">Branch</th>
                            <th class="text-center">Department</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Role</th>
                            <th class="text-center">Available Leaves</th>
                            <th class="text-center">Joining Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Deleted?</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                </div>
                 <!-- Vertically Centered Block Modal -->
        <div class="modal" id="status-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="change_status" action="" method="post">
                        @csrf
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title text-center">Change User Status</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-fw fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content font-size-sm">
                                <p id="warning_message" class="text-center"></p>
                                <input type="hidden" name="user_id" id="user_id">
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
                                <p class="text-center"><span id="user_name"></span> User will be deleted. Are you sure?</p>
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
        <div class="modal" id="restore-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="restore" action="" method="post">
                        @csrf
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title text-center">Restore User</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-fw fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content font-size-sm">
                                <p class="text-center"><span id="restore_user_name"></span> User will be restored. Are you sure?</p>
                                <input type="hidden" name="restore_user_id" id="restore_user_id">
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
        <div class="modal" id="reset-password-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="reset" action="" method="post">
                        @csrf
                        @method('patch')
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title text-center">Restore User</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-fw fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content font-size-sm">
                                <p class="text-center">Are you sure want to reset password for <span id="reset_user_name"></span>?</p>
                                <input type="hidden" name="reset_user_id" id="reset_user_id">
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
        //create table
        jQuery(function(){
            function createTable(){
                $('#dataTable').DataTable( {
                    "scrollX": true,
                    dom: 'Blfrtip',
                    ajax: {
                        type: 'POST',
                        url: '{{ url("user/get_table_data") }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                        }
                    },
                    buttons: [
                        {
                            extend: 'copy',
                            text: 'Copy',
                            title: "User Table"
                        },
                        {
                            extend: 'csv',
                            text: 'CSV',
                            title: "User Table"
                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible'
                            },
                            title: "User Table"
                        },
                        'colvis'
                    ],
                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'All']],
                });
            }
            createTable();
        });
        //end create table

        function show_status_modal(id, msg) {
            var x = document.getElementById('warning_message');
            x.innerHTML = "Are you sure, you want to change status?";
            const url = "{{ url('user/:id/change_status') }}".replace(':id', id);
            $('#change_status').attr('action', url);
            $('#status-modal').modal('show');
        }
        function show_delete_modal(id, name) {
            var x = document.getElementById('user_name');
            x.innerHTML = name;
            const url = "{{ url('user/:id/delete') }}".replace(':id', id);
            $('#delete').attr('action', url);
            $('#delete-modal').modal('show');
        }
        function show_restore_modal(id, name) {
            var x = document.getElementById('restore_user_name');
            x.innerHTML = name;
            const url = "{{ url('user/:id/restore') }}".replace(':id', id);
            $('#restore').attr('action', url);
            $('#restore-modal').modal('show');
        }
        function show_reset_password_modal(id, name) {
            var x = document.getElementById('reset_user_name');
            x.innerHTML = name;
            const url = "{{ url('user/:id/reset_password') }}".replace(':id', id);
            $('#reset').attr('action', url);
            $('#reset-password-modal').modal('show');
        }
     </script>

    <script src="{{ asset('backend/_js/pages/be_tables_datatables.js') }}"></script>


@endsection
