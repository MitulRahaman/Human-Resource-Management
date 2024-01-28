@extends('backend.layouts.master')
@section('css_after')
    <style >
        .spinner {
            display: none;
        }
    </style>
@endsection
@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('asset/asset') }}">Assets</a></li>
            <li class="breadcrumb-item">Add</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
        @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Add Asset</h3>
            </div>
            <form class="js-validation form-prevent-multiple-submission" action="{{ url('asset/store') }}" id="form" method="POST" enctype="multipart/form-data" >
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push ">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-username">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-prevent-multiple-submission" id="name" name="name"   placeholder="Enter a name.." required>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Asset type <span class="text-danger">*</span></label>
                                        <select class="js-select2 form-control input-prevent-multiple-submission" id="type_id" name="type_id" style="width: 100%;" data-placeholder="Choose Asset type.." required>
                                            <option></option>
                                            @foreach ($asset_type as $type)
                                                <option value='{{ $type->id }}' style="color:black"> {{ $type->name }} </option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Sl_no </label>
                                    <input type="number" class="form-control input-prevent-multiple-submission" id="sl_no" name="sl_no"   placeholder="Enter a sl_no.." >
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Branch<span class="text-danger">*</span></label>
                                        <select class="js-select2 form-control input-prevent-multiple-submission" id="branch_id" name="branch_id" style="width: 100%;" data-placeholder="Choose branch.." required>
                                            <option></option>
                                            @foreach ($branches as $branch)
                                                <option value='{{ $branch->id }}' style="color:black"> {{ $branch->name }} </option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-username" class="input-prevent-multiple-submission">Image</label><br>
                                    <input type="file"  id="url" name="url">
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Specification </label>
                                    <input type="text" class="form-control input-prevent-multiple-submission" id="specification" name="specification"   placeholder="Enter specification.." >
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Vendor </label>
                                    <input type="text" class="form-control input-prevent-multiple-submission" id="purchase_at" name="purchase_at"   placeholder="Enter vendor.." >
                                </div>

                                <div class="form-group">
                                    <label for="val-username">Purchase By</label>
                                        <select class="js-select2 form-control input-prevent-multiple-submission" id="purchase_by" name="purchase_by" style="width: 100%;" data-placeholder="Enter who purchased.." >
                                            <option></option>
                                            @foreach ($users as $user)
                                                <option value='{{ $user->id }}' style="color:black"> {{ $user->full_name }} </option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Purchase Price </label>
                                    <input type="number" step="0.01" min="0" class="form-control input-prevent-multiple-submission" id="purchase_price" name="purchase_price"   placeholder="Enter purchased price.." >
                                </div>
                            </div>
                        </div>
                        <!-- END Regular -->

                        <!-- Submit -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" class="btn btn-alt-primary button-prevent-multiple-submission" id="submit">
                                    <i class="spinner fa fa-spinner fa-spin"></i>Submit
                                </button>
                            </div>
                        </div>
                        <!-- END Submit -->
                    </div>
                </div>
            </form>
            <!-- jQuery Validation -->
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
@endsection

@section('js_after')

    <script src="{{ asset('backend/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/jquery-validation/additional-methods.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>
    <script>
        $('.form-prevent-multiple-submission').on('submit',function() {
            $('.button-prevent-multiple-submission').attr('disabled', 'true');
            $('.spinner').show();
        })
        $('.input-prevent-multiple-submission').on('keypress' ,function() {
            $('.button-prevent-multiple-submission').removeAttr('disabled');
            $('.spinner').hide();
        })
        $('.input-prevent-multiple-submission').on('change' ,function() {
            $('.button-prevent-multiple-submission').removeAttr('disabled');
            $('.spinner').hide();
        })
    </script>
@endsection
