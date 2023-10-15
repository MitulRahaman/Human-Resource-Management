@extends('layouts.backend.master')
@section('css_after')
    <link rel="stylesheet" href="{{ asset('backend/js/plugins/select2/css/select2.min.css') }}">
@endsection
@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('menu/menu') }}">Menus</a></li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
    @include('layouts.backend.error_msg')
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Edit Permission</h3>
            </div>
            <!-- jQuery Validation (.js-validation class is initialized in js/pages/be_forms_validation.min.js which was auto compiled from _js/pages/be_forms_validation.js) -->
            <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
            <form class="js-validation" action="{{ url('menu/update') }}" method="POST">
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-8 col-xl-5">
                                <div class="form-group">
                                    <label for="val-title">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ $menu_info->title }}" placeholder="Enter a title.." required>
                                </div>
                                <div class="form-group">
                                    <label for="val-url">Url</label>
                                    <textarea class="form-control" id="url" name="url" rows="5" placeholder="Ex: https://wejet.app">{{ $menu_info->url ?? "" }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="val-icon">Icon</label>
                                    <input type="text" class="form-control" id="icon" name="icon" value="{{ $menu_info->icon ?? "" }}" placeholder="Ex: fa fa-check">
                                </div>
                                <div class="form-group">
                                    <label for="val-description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="What it is used for?">{{ $menu_info->description ?? "" }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="val-order">Order</label>
                                    <input type="number" class="form-control" id="menu_order" name="menu_order" value="{{ $menu_info->menu_order }}"
                                           placeholder="Ex: 5" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                </div>
                                <div class="form-group">
                                    <label for="val-parent-menu">Parent Menu</label>
                                    <select class="js-select2 form-control" id="parent_menu" name="parent_menu" style="width: 100%;" data-placeholder="Choose one..">
                                        <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                        @if (isset($menus))
                                            @foreach($menus as $menu)
                                                <option value="{{ $menu->id }}" {{ $menu_info->parent_menu == $menu->id ? 'selected' : '' }}>{{ $menu->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <input type="hidden" name="id" id="id" value="{{ $menu_info->id }}">
                            </div>
                        </div>
                        <!-- END Regular -->

                        <!-- Submit -->
                        <div class="row items-push">
                            <div class="col-lg-7 offset-lg-4">
                                <button type="submit" class="btn btn-alt-primary">Update</button>
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
    <script src="{{ asset('backend/js/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>

    <script>jQuery(function(){One.helpers(['select2']);});</script>
@endsection
