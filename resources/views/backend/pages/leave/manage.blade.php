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
        <a href="{{ url('leave/add') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Add Leave Type
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
                            <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-custom" name="example-flatpickr-custom" placeholder="YYYY" data-date-format="Y">
                        </div>
                        <div class="form-group col-xl-5">
                        <button type="button" class="btn btn-dark ml-4">
                             Find
                        </button>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-center">Sl no.</th>
                                <th style="width: 60%;">Name</th>
                                <th>Total leave</th> 
                        </thead>
                        <tbody>
                        <?php $x = 1 ?>
                            @forelse ($leaves as $leave)
                                <tr>
                                    <td class="text-center font-size-sm">{{$x++}}</td>
                                    <td class="font-w600 font-size-sm">
                                        <a href="#">{{$leave->name}}</a>
                                    </td>
                                    <td class="text-center font-size-sm">#</td>
                                </tr>
                            @empty
                            <h4 class="text-info">No data available</h4>
                            @endforelse
                        </tbody>
                    </table>
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

       
    <script>jQuery(function(){One.helpers('flatpickr');});</script>

<!-- 
    <script> 
        $(document).ready(function() {
            $('#dataTable').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ],
                columnDefs: [ {
                    targets: -1,
                    visible: false
                } ]
            } );
        } );
    </script> -->
   

    <script src="{{ asset('backend/_js/pages/be_tables_datatables.js') }}"></script>


@endsection
