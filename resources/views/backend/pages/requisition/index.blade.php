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
    @if($requestRequisitionPermission)
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('requisition/request') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Add Request
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
                <h3 class="block-title">Manage Requisition</h3>
            </div>
            <div class="block-content block-content-full">

                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons " id="requisition_table">

                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Employee ID</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Asset Type</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Specification</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Remarks</th>
                            <th >Action</th>
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
                                        <h3 class="block-title text-center">Approve</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        <p class="text-center"> You want to approve <span id="approve_requisition"></span>, are you sure?</p>
                                        <input type="hidden" name="remarks" id="remarks" required>
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
                                        <h3 class="block-title text-center">Approve</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        <p class="text-center"> You want to reject <span id="reject_requisition"></span>, are you sure?</p>
                                        <input type="hidden" name="remarks" id="remarks" required>
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
                <div class="modal" id="deliver-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form id="deliver" action="" method="post">
                                @csrf
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title text-center">Delivered</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        <p class="text-center"> You want to deliver <span id="deliver_requisition"></span>, select it first.</p>
                                        <input type="hidden" name="remarks" id="remarks" required>
                                        <div class="form-group">
                                            <label for="val-suggestions">Select Asset<span class="text-danger">*</span></label>
                                            <select class="js-select2 form-control input-prevent-multiple-submission" id="asset_id" name="asset_id" style="width: 100%;" data-placeholder="Choose asset to deliver.." required>
                                                <option></option>
                                            </select>
                                        </div>
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
            $('#requisition_table').DataTable().destroy();
            var dtable = $('#requisition_table').DataTable({
                responsive: true,
                ajax: '{{ url('requisition/get_requisition_data') }}',
                paging: true,
                dom: 'B<"top"<"left-col"l><"right-col"f>>rtip',
                retrieve: true,
                "order": [[ 0, "asc" ]],
                buttons : [{
                    extend: 'copy',
                    text: 'Copy',
                    className: 'button',
                    title: "Requisition Table"
                },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'button' ,
                        exportOptions:  {
                            columns: [0, 1,2,3,4,5,6,7]
                        },
                        title: "Requisition Table"

                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'button' ,
                        exportOptions:  {
                            columns: [0, 1,2,3,4,5,6,7]
                        },
                        title: "Requisition Table"
                    },
                ],
                lengthMenu: [[ 10, 25, 50, -1], [ 10, 25, 50, 'All']],
            });
            dtable.buttons().container().addClass('center-align-buttons');
        });

        function show_approve_modal(id, name) {
            var x = document.getElementById('approve_requisition');
            x.innerHTML = name;
            const url = "{{ url('requisition/status/:id/approve') }}".replace(':id', id);
            $('#approve').attr('action', url);
            $('#approve-modal').modal('show');
        }
        function show_reject_modal(id, name) {
            var x = document.getElementById('reject_requisition');
            x.innerHTML = name;
            const url = "{{ url('requisition/status/:id/reject') }}".replace(':id', id);
            $('#reject').attr('action', url);
            $('#reject-modal').modal('show');
        }
        function show_deliver_modal(id, name) {
            var x = document.getElementById('deliver_requisition');
            x.innerHTML = name;
            $.ajax({
                url: '{{ url('requisition/fetch_assets_to_deliver') }}',
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#asset_id').empty();
                    console.log(data);
                    for (var i = 0; i < data.length; i++)
                    {
                        var asset =  '<option value="' + data[i]['id'] + '" style="color:black">Asset: ' + data[i]['name'] ;
                        if(data[i]['user_name']) asset+= ', User: '+ data[i]['user_name'];
                        asset+='</option>';
                        $('#asset_id').append(asset);
                    }
                }
            });
            const url = "{{ url('requisition/status/:id/deliver') }}".replace(':id', id);
            $('#deliver').attr('action', url);
            $('#deliver-modal').modal('show');
        }
    </script>
@endsection
