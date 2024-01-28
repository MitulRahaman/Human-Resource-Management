@extends('backend.layouts.master')

@section('content')

    <div class="content">

        <div class="block block-rounded ">
            <div class="block-header">
                <h3 class="block-title">Notes<span class="text-danger">*</span></h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                <form class="js-validation" action="{{ url('meeting/add/notes') }}" method="POST" >
                    @csrf
                    <div class="form-group">
                        <textarea id="js-ckeditor5-classic" name="notes"  required>{{$note}} </textarea>
                    </div>
                    <input type="hidden" value="{{$id}}" id="id" name="id">
                    <div class="block-content block-content-full text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js_after')

    <script src="{{ asset('backend/js/plugins/ckeditor5-classic/build/ckeditor.js') }}"></script>
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>

    <!-- Page JS Code -->
    <script>jQuery(function(){One.helpers(['ckeditor5']);});</script>

@endsection

