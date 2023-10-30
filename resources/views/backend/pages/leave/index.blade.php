@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.colVis.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.colVis2.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/flatpickr/flatpickr.min.css') }}">   
@endsection
@section('page_action')
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('leave/manage') }}">
            <button type="button" class="btn btn-dark mr-3 mb-3">  
            <i class="fa fa-cog mr-1"></i> Manage Leave
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
                <label for="example-flatpickr-custom">Choose a year</label>
                    <div class="form-row">
                        <div class="form-group col-xl-7">    
                            <input type="text" class="js-flatpickr form-control bg-white" id="year" name="example-flatpickr-custom" placeholder="YYYY" data-date-format="Y" value="{{ date('Y') }}">
                        </div>
                        <div class="form-group col-xl-5">
                        <button type="button" class="btn btn-dark ml-4" id="find">
                             Find
                        </button>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped table-vcenter" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-center">Sl no.</th>
                                <th style="width: 40%;">Name</th>
                                <th class="text-center" style="width: 15%;">for Year</th>
                                <th class="text-center">Total leave</th> 
                                <th class="text-center">Action</th> 
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <!-- Add total leave Modal -->
                    <div class="modal fade" id="modal-block-fadein" tabindex="-1" role="dialog" aria-labelledby="modal-block-slideup" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-slideup" role="document">
                            <div class="modal-content">
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <div>
                                            <h3 class="block-title text-white">Add total leave</h3> 
                                        </div>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <form class="js-validation" action="{{ url('leave/addTotalLeave') }}" method="POST">
                                        @csrf
                                        <div class="block block-rounded">
                                            <div class="block-content block-content-full">
                                                <div class="row items-push">
                                                    <div class="col-lg-8 col-xl-5">
                                                        <div class="form-group">
                                                            <label for="val-title">Total Leave <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" id="totalLeave" name="totalLeave" value="{{ old('totalLeave') }}" placeholder="Enter total leave.."> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row items-push">
                                                    <div class="col-lg-7 offset-lg-4">
                                                        <button type="submit" class="btn btn-alt-primary" id="submit">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Add total leave Modal -->
                    <!-- Update total leave Modal -->
                    <div class="modal fade" id="modal-block-fadein" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-fadein" role="document">
                            <div class="modal-content">
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <div>
                                            <h3 class="block-title text-white">Update total Leave</h3> 
                                        </div>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        <p>Are you sure want to delete? </p>
                                    </div>
                                    <div class="d-flex justify-content-between p-4 border-top">
                                        <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
                                        <form  method="post"> 
                                            @csrf
                                            @method('delete')
                                                <button type="submit" class="btn btn-primary">Ok</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Update total leave Modal -->
                </div>
            </div>
        </div>
   
@endsection

@section('js_after')

    

    <script src="{{ asset('backend/js/oneui.app.min.js') }}"></script>    
    <script src="{{ asset('backend/js/oneui.core.min.js') }}"></script>

    <!-- Page JS Code -->

    <script src="{{ asset('backend/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('backend/js/plugins/flatpickr/flatpickr.min.js') }}"></script>

       
    <script>
        jQuery(function(){
            One.helpers('flatpickr');
            $("#datestart").flatpickr();
            $('#dataTable').DataTable( {
                dom: 'Bfrtip',
                ajax: {
                    type: 'POST',
                    url: '{{ url("leave_types/get_data") }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        year: $('#year').val()
                    }
                },
                buttons: [
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ]
            });

            document.getElementById('find').addEventListener("click", function() { 
                $('#dataTable').DataTable().destroy();
                One.helpers('flatpickr');
                $("#datestart").flatpickr();
                $('#dataTable').DataTable( {
                    dom: 'Bfrtip',
                    ajax: {
                        type: 'POST',
                        url: '{{ url("leave_types/get_data") }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            year: $('#year').val()
                        }
                    },
                    buttons: [
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        'colvis'
                    ]
                });
            });  
        });
    
    </script>

    <script src="{{ asset('backend/_js/pages/be_tables_datatables.js') }}"></script>


@endsection
