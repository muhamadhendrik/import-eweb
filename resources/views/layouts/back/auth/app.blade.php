<!doctype html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('back/assets') }}/"
    data-template="vertical-menu-template" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title> {{ config('app.name')}} | @yield('title', 'Login')</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('back/assets/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('back/assets') }}/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="{{ asset('back/assets') }}/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="{{ asset('back/assets') }}/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->

    <link rel="stylesheet" href="{{ asset('back/assets') }}/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('back/assets') }}/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />

    <link rel="stylesheet" href="{{ asset('back/assets') }}/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('back/assets') }}/vendor/libs/node-waves/node-waves.css" />

    <link rel="stylesheet" href="{{ asset('back/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ asset('back/assets') }}/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('back/assets') }}/vendor/libs/@form-validation/form-validation.css" />
    <link rel="stylesheet" href="{{ asset('back/assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('back/assets') }}/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="{{ asset('back/assets') }}/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('back/assets') }}/vendor/js/template-customizer.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('back/assets') }}/js/config.js"></script>
    @stack('styles')
</head>

<body>
    <!-- Content -->
    @include('layouts.back.partials._alert')

    @yield('content')

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{ asset('back/assets') }}/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('back/assets') }}/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('back/assets') }}/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('back/assets') }}/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('back/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('back/assets') }}/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('back/assets') }}/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('back/assets') }}/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ asset('back/assets') }}/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('back/assets') }}/vendor/libs/@form-validation/popular.js"></script>
    <script src="{{ asset('back/assets') }}/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="{{ asset('back/assets') }}/vendor/libs/@form-validation/auto-focus.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('back/assets') }}/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ asset('back/assets') }}/js/pages-auth.js"></script>

    <script src="{{ asset('back/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

    <script src="{{ asset('back/assets/js/extended-ui-sweetalert2.js') }}"></script>

    @stack('scripts')

</body>

</html>
