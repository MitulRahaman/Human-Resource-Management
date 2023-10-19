@extends('backend.layouts.master')
@section('css_after')
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
@endsection
@section('page_action')
    <div class="mt-3 mt-sm-0 ml-sm-3">
        <a href="{{ url('branch/add') }}">
            <button type="button" class="btn btn-dark mr-1 mb-3">
                <i class="fa fa-fw fa-key mr-1"></i> Add Branch
            </button>
        </a>
    </div>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title m-4">{{ $sub_menu }}</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                        <div class="block-header">
                            <h4 class="block-title mb-2">Available Branch</h4>
                        </div>
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 80px;">ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Deleted?</th>
                                    <th class="d-none d-sm-table-cell" style="width: 50%;">Address</th>
                                    <th class="d-none d-sm-table-cell" style="width: 20%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $x = 1 ?>
                                @forelse ($branches as $b)
                                    <tr>
                                        <td class="text-center font-size-sm">{{$x++}}</td>
                                        <td class="font-w600 font-size-sm">
                                            <a href="#">{{$b->name}}</a>
                                        </td>
                                        <td class="font-w600 font-size-sm">
                                            @if ($b->status == 1)
                                            <span class="badge badge-success">Active</span>
                                            @else
                                            <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="font-w600 font-size-sm">
                                            @if ($b->deleted_at)
                                            <span class="badge badge-danger">Deleted</span>
                                            @endif
                                        </td>
                                        <td class="d-none d-sm-table-cell font-size-sm">
                                        {{$b->address}}<em class="text-muted"></em>
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            <span class="badge">
                                                <div class="row"> 
                                                    <div class="col"> 
                                                        <!-- Delete Confirmation Modal -->
                                                        <div class="modal fade" id="modal-block-fromright_{{$b->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-fromright" aria-hidden="true">
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
                                                                            <form action ="{{ route('branch.destroy', $b->id) }}" method="post"> 
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
                                                        <div class="modal fade" id="modal-block-fromleft_{{$b->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-fromleft" aria-hidden="true">
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
                                                                            <form action ="{{ route('branch.restore', $b->id) }}" method="post"> 
                                                                                @csrf
                                                                                    <button type="submit" class="btn btn-primary">Ok</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- END Restore Confirmation Modal -->
                                                        <a  class="btn btn-sm btn-light " href="#">
                                                            @if ($b->deleted_at)
                                                            <i class="fas fa-trash-restore text-warning mr-1"></i> 
                                                            <button type="button" class="border-0" data-toggle="modal" data-target="#modal-block-fromleft_{{$b->id}}">Restore</button>
                                                            @else
                                                            <i class="fa fa-trash text-danger mr-1"></i> 
                                                            <button type="button" class="border-0" data-toggle="modal" data-target="#modal-block-fromright_{{$b->id}}">Delete</button>
                                                            @endif
                                                        </a>
                                                    </div>
                                                    @if (!$b->deleted_at)
                                                    <div class="col"> 
                                                        <a class="btn btn-sm btn-light" href="{{ route('branch.edit', $b->id) }}">
                                                            <i class="fas fa-edit text-success mr-1"></i> Edit
                                                        </a>
                                                    </div>
                                                    @endif
                                                </div>
                                            
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                <h4 class="text-info">No branches available</h4>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- END Dynamic Table Full -->
            
            </div>
        </div>

        <!-- END Dynamic Table with Export Buttons -->
    </div>
@endsection

@section('js_after')

    <!-- Page JS Code -->

    <script type="text/javascript">
        jQuery(document).ready(function ($) {

        });
    </script>
@endsection
