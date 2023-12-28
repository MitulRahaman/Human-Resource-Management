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

@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">User Assets</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons " id="user_asset_table">
                        <thead>
                        <tr>
                            <th class="text-center ">#</th>
                            <th >Image</th>
                            <th>Employee ID</th>
                            <th>User Name</th>
                            <th >Asset Name</th>
                            <th >Asset Type</th>
                            <th >sl_no</th>
                            <th >Branch</th>
                            <th class="d-none d-sm-table-cell ">Status</th>
                            <th class="d-none d-sm-table-cell ">Condition</th>
                            <th>By Requisition</th>
                            <th >Created At</th>
                            <th >Action</th>
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
                                        <h3 class="block-title text-center">Change Status</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        <p id="warning_message" class="text-center"></p>
                                        <input type="hidden" name="user_asset_id" id="user_asset_id">
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
                <div class="modal" id="asset-condition-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form id="asset_condition" action="" method="post">
                                @csrf
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title text-center">Change Asset Condition</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        <p class="text-center"><span id="change_condition"></span></p>
                                        <input type="hidden" name="remarks" id="remarks" required>
                                        <div class="form-group" id="condition_select">

                                        </div>
                                    </div>
                                    <div class="block-content block-content-full text-right border-top" id="buttons">

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
            $('#user_asset_table').DataTable().destroy();

            var dtable = $('#user_asset_table').DataTable({
                responsive: true,
                ajax: '{{ url('asset/get_user_asset_data') }}',
                paging: true,
                dom: 'B<"top"<"left-col"l><"right-col"f>>rtip',
                retrieve: true,
                "order": [[ 0, "asc" ]],
                buttons : [{
                    extend: 'copy',
                    text: 'Copy',
                    className: 'button',
                    title: "User Assets Table"
                },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'button' ,
                        exportOptions:  {
                            columns: [0, 1,2,3,4,5,6,7,8,9,10]
                        },
                        title: "User Assets Table"
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'button' ,
                        exportOptions:  {
                            columns: [0, 1,2,3,4,5,6,7,8,9,10]
                        },
                        title: "User Assets Table"
                    },
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
            });
            dtable.buttons().container().addClass('center-align-buttons');
        });
        function show_status_modal(id, msg) {
            var x = document.getElementById('warning_message');
            x.innerHTML = "Are you sure, you want to change status?";
            const url = "{{ url('asset/:id/change_user_asset_status') }}".replace(':id', id);
            $('#change_status').attr('action', url);
            $('#status-modal').modal('show');
        }
        function show_condition_modal(id, condition) {
            var x = document.getElementById('change_condition');
            var asset_condition={
                good: {!! json_encode(\Illuminate\Support\Facades\Config::get('variable_constants.asset_condition.good')) !!},
                need_repair: {!! json_encode(\Illuminate\Support\Facades\Config::get('variable_constants.asset_condition.need_repair')) !!},
                damaged: {!! json_encode(\Illuminate\Support\Facades\Config::get('variable_constants.asset_condition.damaged')) !!},
                destroyed: {!! json_encode(\Illuminate\Support\Facades\Config::get('variable_constants.asset_condition.destroyed')) !!}
            };
            if(condition==asset_condition.damaged ||condition==asset_condition.destroyed)
            {
                x.innerHTML = "Can't change asset's condition.";
                $('#condition_select').empty();
                $('#buttons').empty();
            }
            else
            {
                x.innerHTML = "Select changeable condition";
                $('#condition_select').empty();
                $('#buttons').empty();
                $('#condition_select').append('<label for="val-suggestions">Select Condition<span class="text-danger">*</span></label>\n' +
                    '                                            <select class="js-select2 form-control input-prevent-multiple-submission" id="condition" name="condition" style="width: 100%;" data-placeholder="Choose Asset condition.." required>\n' +
                    '                                                <option></option>\n' +
                    '                                            </select>');
                if(condition==asset_condition.good)
                {
                    $('#condition').append('<option value="' + asset_condition.need_repair + '" style="color:black">Need to Repair</option>');
                }
                else
                {
                    $('#condition').append('<option value="' + asset_condition.good + '" style="color:black">Good</option>');
                }
                $('#condition').append('<option value="' + asset_condition.damaged + '" style="color:black">Damaged</option>');
                $('#condition').append('<option value="' + asset_condition.destroyed + '" style="color:black">Destroyed</option>');
                $('#buttons').append('<button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>\n' +
                    '                                        <button type="submit" class="btn btn-primary">Submit</button>');
            }
            const url = "{{ url('asset/:id/change_condition') }}".replace(':id', id);
            $('#asset_condition').attr('action', url);
            $('#asset-condition-modal').modal('show');
        }
    </script>
@endsection
