@extends('backend.layouts.master')

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
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Add Asset</h3>
            </div>

            <form class="js-validation" action="{{ url('asset/store') }}" id="form" method="POST" >
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push ml-10">
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group">
                                    <label for="val-username">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"   placeholder="Enter a name.." required>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Asset type <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <select class="js-select2 form-control" id="type_id" name="type_id" style="width: 100%;" data-placeholder="Choose Asset type.." required>
                                            <option></option>
                                            @foreach ($asset_type as $type)
                                                <option value='{{ $type->id }}' style="color:black"> {{ $type->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Sl_no </label>
                                    <input type="number" class="form-control" id="sl_no" name="sl_no"   placeholder="Enter a sl_no.." >
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Branch<span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <select class="js-select2 form-control" id="branch_id" name="branch_id" style="width: 100%;" data-placeholder="Choose branch.." required>
                                            <option></option>
                                            @foreach ($branches as $branch)
                                                <option value='{{ $branch->id }}' style="color:black"> {{ $branch->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Image Url </label>
                                    <input type="text" class="form-control" id="url" name="url"   placeholder="Enter a url for image.." >
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Specification </label>
                                    <input type="text" class="form-control" id="specification" name="specification"   placeholder="Enter specification.." >
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Vendor </label>
                                    <input type="text" class="form-control" id="purchase_at" name="purchase_at"   placeholder="Enter vendor.." >
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Purchase By </label>
                                    <input type="text" class="form-control" id="purchase_by" name="purchase_by"   placeholder="Enter who purchased.." >
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Purchase Price </label>
                                    <input type="text" class="form-control" id="purchase_price" name="purchase_price"   placeholder="Enter purchased price.." >
                                </div>
                            </div>
                        </div>
                        <!-- END Regular -->

                        <!-- Submit -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" class="btn btn-alt-primary" id="submit" >Submit</button>
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
@endsection
