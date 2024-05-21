@if (\Session::has('success'))
    <div class="toast-container position-fixed bottom-0 start-0 p-3">
        <div id="successToast" class="toast text-bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {!! \Session::get('success') !!}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#successToast').toast('show');
        });
    </script>
@endif

@if (\Session::has('error'))
    <div class="toast-container position-fixed bottom-0 start-0 p-3">
        <div id="errorToast2" class="toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {!! \Session::get('error') !!}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#errorToast2').toast('show');
        });
    </script>
@endif

@if ($errors->any())
    <div class="toast-container position-fixed bottom-0 start-0 p-3">
        @foreach ($errors->all() as $error)
            <div class="toast text-bg-danger errorToast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ $error }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endforeach

    </div>
    <script>
        $(document).ready(function() {
            $('.errorToast').toast('show');
        });
    </script>
@endif
