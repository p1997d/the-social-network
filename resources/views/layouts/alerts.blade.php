@if (\Session::has('success'))
    <div class="alert alert-success alert-dismissible">
        <div>{!! \Session::get('success') !!}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (\Session::has('error'))
    <div class="alert alert-danger alert-dismissible">
        <div>{!! \Session::get('error') !!}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
