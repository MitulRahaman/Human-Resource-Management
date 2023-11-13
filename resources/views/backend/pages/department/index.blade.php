@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.colVis.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.colVis2.css') }}">
@endsection
@section('page_action')
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('department/add') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Add Department
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
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js --> 
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%;">Sl no.</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Deleted?</th>
                                <th style="width: 20%;">Branches</th>
                                <th class="d-none d-sm-table-cell" style="width: 50%;">Description</th>
                                <th class="d-none d-sm-table-cell" style="width: 20%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $x = 1 ?>
                            @forelse ($departments as $department)
                                <tr>
                                    <td class="text-center font-size-sm">{{$x++}}</td>
                                    <td class="font-w600 font-size-sm">
                                        <a href="#">{{$department->name}}</a>
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        @if ($department->status == 1 && $department->deleted_at == null)
                                            <a href="{{ route('department.status', $department->id) }}">
                                                <span class="badge badge-success">Active</span>
                                            </a>
                                        @else
                                            <a href="{{ route('department.status', $department->id) }}">
                                                <span class="badge badge-warning">Inactive</span>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        @if ($department->deleted_at)
                                        <span class="badge badge-danger">Deleted</span>
                                        @endif
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        <span>{{$department->branches}}</span>
                                    </td>
                                    <td class="d-none d-sm-table-cell font-size-sm">
                                    {{$department->description}}<em class="text-muted"></em>
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <span class="badge">
                                            <div class="row"> 
                                                <div class="col"> 
                                                    <a  class="btn btn-sm btn-light " href="#">
                                                        @if ($department->deleted_at)
                                                        <button type="button" class="border-0" data-toggle="modal" data-target="#modal-block-fromleft_{{$department->id}}"> <i class="fas fa-trash-restore text-warning mr-1"></i> Restore</button>
                                                        @else
                                                        <button type="button" class="border-0" data-toggle="modal" data-target="#modal-block-fromright_{{$department->id}}"> <i class="fa fa-trash text-danger mr-1"></i> Delete</button>
                                                        @endif
                                                    </a>
                                                </div>
                                                @if (!$department->deleted_at)
                                                <div class="col"> 
                                                    <a class="btn btn-sm btn-light" href="{{ route('department.edit', $department->id) }}">
                                                        <i class="fas fa-edit text-success mr-1"></i> Edit
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                        </span>
                                    </td>
                                </tr>
                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="modal-block-fromright_{{$department->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-fromright" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-fromright" role="document">
                                        <div class="modal-content">
                                            <div class="block block-rounded block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <div>
                                                        <h3 class="block-title text-white">Warning</h3> 
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
                                                    <form action ="{{ route('department.destroy', $department->id) }}" method="post"> 
                                                        @csrf
                                                        @method('delete')
                                                            <button type="submit" class="btn btn-primary">Ok</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END Delete Confirmation Modal -->
                                <!-- Restore Confirmation Modal -->
                                <div class="modal fade" id="modal-block-fromleft_{{$department->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-fromleft" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-fromleft" role="document">
                                        <div class="modal-content">
                                            <div class="block block-rounded block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <div>
                                                        <h3 class="block-title text-white">Warning</h3> 
                                                    </div>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-fw fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="block-content font-size-sm">
                                                    <p>Are you sure want to restore?</p>
                                                </div>
                                                <div class="d-flex justify-content-between p-4 border-top">
                                                    <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
                                                    <form action ="{{ route('department.restore', $department->id) }}" method="post"> 
                                                        @csrf
                                                            <button type="submit" class="btn btn-primary">Ok</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END Restore Confirmation Modal -->
                            @empty
                            <h4 class="text-info">No departments available</h4>
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
   

    <script src="{{ asset('backend/_js/pages/be_tables_datatables.js') }}"></script>


@endsection
