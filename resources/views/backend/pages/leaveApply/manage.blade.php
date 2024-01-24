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
        <a href="{{ url('leaveApply/apply') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Apply for Leave
            </button>
        </a>
        <a href="{{ url('leaveApply/leaveReports') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="si si-settings"></i> Generate Report
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
                                <th class="text-center">No.</th>
                                <th class="text-center">Employee ID</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Subject</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">End date</th>
                                <th class="text-center">Days</th>
                                <th class="text-center">Reason</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Remarks</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal" id="recommend-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form id="recommend" action="" method="post">
                                @csrf
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title text-center">Approve</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group font-size-sm px-6">
                                        <p class="text-center mt-2"><span id="recommend_leave"></span> Give a reason: </p>
                                        <input class="form-control bg-light" type="text" name="recommend-modal-remarks" id="recommend-modal-remarks" value="" required>
                                    </div>
                                    <div class="block-content block-content-full text-right border-top">
                                        <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Recommend</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form id="approve" action="" method="post">
                                @csrf
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title text-center">Approve</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group font-size-sm px-6">
                                        <p class="text-center mt-2"><span id="approve_leave"></span> Approval Note</p>
                                        <input class="form-control bg-light" type="text" name="approve-modal-remarks" id="approve-modal-remarks" style="width: 100%" value="">
                                    </div>
                                    <input type="hidden" id="employeeId" name="employeeId" value="">
                                    <input type="hidden" id="leaveType" name="leaveType" value="">
                                    <input type="hidden" id="startDate" name="startDate" value="">
                                    <input type="hidden" id="endDate" name="endDate" value="">
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
                                        <p class="text-center mt-2"><span id="reject_leave"></span> Rejection Note</p>
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
                    dom: 'Blfrtip',
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
        function show_recommend_modal(id, type, remarks) {
            var x = document.getElementById('recommend_leave');
            x.innerHTML = type;
            const url = "{{ url('leaveApply/status/:id/recommend') }}".replace(':id', id);
            $('#recommend').attr('action', url);
            document.getElementById("recommend-modal-remarks").value = remarks;
            $('#recommend-modal').modal('show');
        }
        function show_approve_modal(id, type, remarks, startDate, endDate, employeeId) {
            var x = document.getElementById('approve_leave');
            x.innerHTML = type;
            const url = "{{ url('leaveApply/status/:id/approve') }}".replace(':id', id);
            $('#approve').attr('action', url);
            document.getElementById("approve-modal-remarks").value = remarks;
            document.getElementById("leaveType").value = type;
            document.getElementById("startDate").value = startDate;
            document.getElementById("endDate").value = endDate;
            document.getElementById("employeeId").value = employeeId;
            $('#approve-modal').modal('show');
        }
        function show_reject_modal(id, type, remarks) {
            var x = document.getElementById('reject_leave');
            x.innerHTML = type;
            const url = "{{ url('leaveApply/status/:id/reject') }}".replace(':id', id);
            $('#reject').attr('action', url);
            document.getElementById("reject-modal-remarks").value = remarks;
            $('#reject-modal').modal('show');
        }
     </script>

    <script src="{{ asset('backend/_js/pages/be_tables_datatables.js') }}"></script>


@endsection
