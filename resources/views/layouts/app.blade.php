<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        @include('includes.head')

        @section('css_global')
        @show

    </head>
    <body class="sb-nav-fixed">

        @include('includes.topnav')

        <div id="layoutSidenav">

            <div id="layoutSidenav_nav">

                @include('includes.sidenav')

            </div>

            <div id="layoutSidenav_content">

                <main>
                    @yield('content')
                </main>

                @include('includes.footer')

            </div>
        </div>

        @section('js_global')
        @show

        @routes

        @section('plugin')
        @show

    </body>
</html>
