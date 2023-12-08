<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title> @yield('title')</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('template/src/assets/img/favicon.ico') }}" />
        <link href="{{ asset('template/layouts/vertical-dark-menu/css/light/loader.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('template/layouts/vertical-dark-menu/css/dark/loader.css') }}" rel="stylesheet"
            type="text/css" />
        <script src="{{ asset('template/layouts/vertical-dark-menu/loader.js') }}"></script>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
        <link href="{{ asset('template/src/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('template/layouts/vertical-dark-menu/css/light/plugins.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('template/src/assets/css/light/authentication/auth-boxed.css') }}" rel="stylesheet"
            type="text/css" />

        <link href="{{ asset('template/layouts/vertical-dark-menu/css/dark/plugins.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('template/src/assets/css/dark/authentication/auth-boxed.css') }}" rel="stylesheet"
            type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->

        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link rel="stylesheet" type="text/css" href="{{ asset('template/src/assets/css/light/elements/alert.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('template/src/assets/css/dark/elements/alert.css') }}">
        <!--  END CUSTOM STYLE FILE  -->

    </head>

    <body class="form">

        <!-- BEGIN LOADER -->
        <div id="load_screen">
            <div class="loader">
                <div class="loader-content">
                    <div class="spinner-grow align-self-center"></div>
                </div>
            </div>
        </div>

        <div class="auth-container d-flex">
            <div class="container mx-auto align-self-center">
                <div class="row">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
        <script src="{{ asset('template/src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- END GLOBAL MANDATORY SCRIPTS -->

    </body>

</html>