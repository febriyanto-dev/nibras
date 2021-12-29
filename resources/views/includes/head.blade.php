    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{(isset($title)) ? $title.' - '.config('app.name') : config('app.name')}}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/icon.png') }}">

    @include('includes.script')
