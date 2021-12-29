
    @section('css_global')
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

    <style>
        .select2-container .select2-selection--single{
            height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 32px;
        }
    </style>
    @endsection

    @section('js_global')
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    <script src="{{ asset('js/main/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/forms/validation/validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/loaders/blockui.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    @endsection
