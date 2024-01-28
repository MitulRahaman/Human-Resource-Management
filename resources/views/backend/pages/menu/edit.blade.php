@extends('backend.layouts.master')
@section('css_after')

    <link rel="stylesheet" href="{{asset('backend/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/dropzone/dist/min/dropzone.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/js/plugins/flatpickr/flatpickr.min.css')}}">

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
        @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Edit Menu</h3>
            </div>

            <form class="js-validation" id='form' action='{{ url('menu/' . $menu_info->id . '/update')}}' method="POST">
                @csrf
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push ">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-username">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ $menu_info->title }}" placeholder="Enter a title.." >
                                </div>
                                <div class="form-group">
                                    <label for="val-username">URL</label>
                                    <input type="text" class="form-control" id="url" name="url" value="{{ $menu_info->url }}" placeholder="Enter a url.." >
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Icon</label>
                                    <input type="text" class="form-control" id="icon" name="icon" value="{{ $menu_info->icon }}" placeholder="Enter a icon.." >
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Menu_order</label>
                                    <input type="number" step="1" class="form-control" id="menu_order" name="menu_order" value="{{ $menu_info->menu_order }}" placeholder="Enter a menu_order.." >
                                </div>
                                <div class="form-group">
                                    <label for="val-username">Parent_menu</label>
                                        <select class="js-select2 form-control" id="parent_menu" name="parent_menu" style="width: 100%;" data-placeholder="Choose parent menu..">
                                            <option></option>
                                            @if ($menu_info->parent_menu)
                                                <option value='{{ $menu_info->parent_menu }}' selected> {{ $parent_menu_title[0] }} </option>
                                            @endif
                                            @foreach ($menus as $menu)
                                                @if ($menu_info->parent_menu != $menu->id)
                                                    <option value='{{ $menu->id }}' style="color:black" > {{ $menu->title }} </option>
                                                @endif
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-suggestions">Permissions</label>
                                        <select class="js-select2 form-control" id="permissions" name="permissions[]" style="width: 100%;" data-placeholder="Choose Permissions for the Menu.." multiple>
                                            <option></option>
                                            @foreach ($permissions as $permission)
                                                <option value='{{ $permission->id }}' @if($permission->selected == "yes") selected @endif> {{ $permission->name }} </option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="val-suggestions">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="What it is used for?">{{ $menu_info->description ?? "" }}</textarea>
                                </div>
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
    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>
    <!-- Page JS Plugins -->

    <script src="{{asset('backend/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/dropzone/dropzone.min.js')}}"></script>
    <script src="{{asset('backend/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
    <script>jQuery(function(){One.helpers(['flatpickr', 'datepicker', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);});</script>


@endsection
