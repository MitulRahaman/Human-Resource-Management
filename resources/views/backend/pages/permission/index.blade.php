@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">

@endsection
@section('page_action')
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('permission/add') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Add Permission
            </button>
        </a>
    </div>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Manage Permissions</h3>
            </div>
            <div class="block-content block-content-full">

                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons " id="permission_table">

                    <div class="row">
                        {{--<div class="col-sm-12">--}}
                            {{--<div class="text-center bg-body-light py-2 mb-2">--}}
                                {{--<div class="dt-buttons">--}}
                                    {{--<button class="dt-button buttons-copy buttons-html5 btn btn-sm btn-alt-primary" tabindex="0" aria-controls="DataTables_Table_3" type="button" onClick="copytable()" value="Copy to Clipboard"><span>Copy</span></button>--}}
                                    {{--<button class="dt-button buttons-csv buttons-html5 btn btn-sm btn-alt-primary" tabindex="0" aria-controls="DataTables_Table_3" type="button" onclick="download_table_as_csv('permission_table');"><span>CSV</span></button>--}}
                                    {{--<button class="dt-button buttons-print btn btn-sm btn-alt-primary" tabindex="0" aria-controls="DataTables_Table_3" type="button" value="Export" onclick="Export()"><span>PDF</span></button>--}}
                                    {{--<button class="dt-button buttons-print btn btn-sm btn-alt-primary" tabindex="0" aria-controls="DataTables_Table_3" type="button" onclick="window.print()"><span>Print</span></button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <thead>
                            <tr>
                                <th class="text-center ">#</th>
                                <th >Name</th>
                                <th >Slug</th>

                                <th class="d-none d-sm-table-cell " style="width: 20%;">Description</th>
                                <th class="d-none d-sm-table-cell ">Status</th>
                                <th >Deleted</th>
                                <th >Created At</th>
                                <th >Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </div>
                    </div>
                </table>
                <!-- Vertically Centered Block Modal -->
                <div class="modal" id="status-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="{{ url('permission/change_status') }}" method="post">
                                @csrf
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title text-center">Change Permission Status</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        <p id="warning_message" class="text-center"></p>
                                        <input type="hidden" name="permission_id" id="permission_id">
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
                            <form action="{{ url('permission/delete') }}" method="post">
                                @csrf
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title text-center">Delete Permission</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        <p class="text-center"><span id="permission_name"></span> Permission will be deleted. Are you sure?</p>
                                        <input type="hidden" name="delete_permission_id" id="delete_permission_id">
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
                            <form action="{{ url('permission/restore') }}" method="post">
                                @csrf
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title text-center">Restore Permission</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        <p class="text-center"><span id="restore_permission_name"></span> Permission will be restored. Are you sure?</p>
                                        <input type="hidden" name="restore_permission_id" id="restore_permission_id">
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
    {{--<script src="{{ asset('backend/js/oneui.app.min.js') }}"></script>--}}
    {{--<script src="{{ asset('backend/js/oneui.core.min.js') }}"></script>--}}

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
            $('#permission_table').DataTable().destroy();

            var dtable = $('#permission_table').DataTable({
                responsive: true,
                ajax: '{{ url('permission/get_permission_data') }}',
                paging: true,

                dom: 'Bfrtip',
                retrieve: true,

                // "order": [[ 0, "asc" ]],
            });
        });

        function show_status_modal(id, msg) {
            var x = document.getElementById('warning_message');
            x.innerHTML = "Are you sure, you want to change status?";
            $('#permission_id').val(id);
            $('#status-modal').modal('show');
        }

        function show_delete_modal(id, name) {
            var x = document.getElementById('permission_name');
            x.innerHTML = name;
            $('#delete_permission_id').val(id);
            $('#delete-modal').modal('show');
        }

        function show_restore_modal(id, name) {
            var x = document.getElementById('restore_permission_name');
            x.innerHTML = name;
            $('#restore_permission_id').val(id);
            $('#restore-modal').modal('show');
        }
    </script>
    {{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>--}}
    {{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>--}}
    {{--<script type="text/javascript">--}}
        {{--function Export() {--}}
            {{--html2canvas(document.getElementById('permission_table'), {--}}
                {{--onrendered: function (canvas) {--}}
                    {{--var data = canvas.toDataURL();--}}
                    {{--var docDefinition = {--}}
                        {{--content: [{--}}
                            {{--image: data,--}}
                            {{--width: 500--}}
                        {{--}]--}}
                    {{--};--}}
                    {{--pdfMake.createPdf(docDefinition).download("PermissionTable.pdf");--}}
                {{--}--}}
            {{--});--}}
        {{--}--}}
    {{--</script>--}}
    {{--<script type="text/javascript">--}}

        {{--function copytable() {--}}
            {{--var urlField = document.getElementById('permission_table')--}}
            {{--var range = document.createRange()--}}
            {{--range.selectNode(urlField)--}}
            {{--window.getSelection().addRange(range)--}}
            {{--document.execCommand('copy')--}}
        {{--}--}}
    {{--</script>--}}
    {{--<script>--}}
        {{--function download_table_as_csv(table_id, separator = ',') {--}}
            {{--// Select rows from table_id--}}
            {{--var rows = document.querySelectorAll('table#' + table_id + ' tr');--}}
            {{--// Construct csv--}}
            {{--var csv = [];--}}
            {{--for (var i = 0; i < rows.length; i++) {--}}
                {{--var row = [], cols = rows[i].querySelectorAll('td, th');--}}
                {{--for (var j = 0; j < cols.length-2; j++) {--}}
                    {{--// Clean innertext to remove multiple spaces and jumpline (break csv)--}}
                    {{--var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')--}}
                    {{--// Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)--}}
                    {{--data = data.replace(/"/g, '""');--}}
                    {{--// Push escaped string--}}
                    {{--row.push('"' + data + '"');--}}
                {{--}--}}
                {{--csv.push(row.join(separator));--}}
            {{--}--}}
            {{--var csv_string = csv.join('\n');--}}
            {{--// Download it--}}
            {{--var filename = 'export_' + table_id + '_' + new Date().toLocaleDateString() + '.csv';--}}
            {{--var link = document.createElement('a');--}}
            {{--link.style.display = 'none';--}}
            {{--link.setAttribute('target', '_blank');--}}
            {{--link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));--}}
            {{--link.setAttribute('download', filename);--}}
            {{--document.body.appendChild(link);--}}
            {{--link.click();--}}
            {{--document.body.removeChild(link);--}}
        {{--}--}}
    {{--</script>--}}
@endsection
