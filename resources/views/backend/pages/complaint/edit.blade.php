@extends('backend.layouts.master')
@section('css_after')
@endsection
@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('complaint/manage') }}">Manage complaint</a></li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="content">
    @include('backend.layouts.error_msg')
        <div class="block block-rounded block-content col-sm-6">
            <div class="block-header">
                <h3 class="block-title">Update Complaint</h3>
            </div>

            <!-- jQuery Validation (.js-validation class is initialized in js/pages/be_forms_validation.min.js which was auto compiled from _js/pages/be_forms_validation.js) -->
            <form class="js-validation form-prevent-multiple-submission" action="{{ url('complaint/update', $complaint->id) }}" method="POST" id="form" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-title">Title<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ $complaint->title }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="val-byWhom">By Whom<span class="text-danger">*</span></label>
                                    <input class="form-control" id="byWhom" name="byWhom" value="{{ auth()->user()->full_name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="val-againstWhom">Againts Whom<span class="text-danger">*</span></label>
                                    <select class="form-control" id="againstWhom" name="againstWhom" required>
                                        @forelse ($users as $user)
                                        <option value="{{ $user->id }}" {{ $complaint->against_whom == $user->id ? 'selected' : '' }}>{{ $user->full_name }}</option>
                                        @empty
                                        <p> </p>
                                        @endforelse
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="val-description">Please tell your reason<span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required>{{ $complaint->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="val_photo">Select File</label><br>
                                    <input type="file" class=" " name="photo" id="photo" /><br>
                                </div>
                            </div>
                        </div>

                        <div class="row items-push">
                            <div class="col-lg-6 offset-lg-5">
                                <button type="submit" class="btn btn-alt-primary" id="submit">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- End jQuery Validation -->
        </div>
    </div>

@endsection

@section('js_after')
    <script src="{{ asset('backend/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/jquery-validation/additional-methods.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>
@endsection
