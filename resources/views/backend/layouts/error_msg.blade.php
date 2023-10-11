@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-dark alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p class="mb-0">{{ $error }}</p>
        </div>
    @endforeach
@elseif(session('error'))
    <div class="alert alert-dark alert-dismissable" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <p class="mb-0">{{ session('error') }}</p>
    </div>
@elseif(session('success'))
    <div class="alert alert-success alert-dismissable" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <p class="mb-0">{{ session('success') }}</p>
    </div>
@endif
