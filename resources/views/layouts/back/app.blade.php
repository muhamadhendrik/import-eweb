<!doctype html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('back/assets') }}/" data-template="vertical-menu-template" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ config('app.name') }} - @yield('title')</title>

    <meta name="description" content="" />

    @include('layouts.back.partials._head')

    @vite(['resources/js/app.js'])

    @stack('styles')
</head>

<body>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('layouts.back.partials._sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                @include('layouts.back.partials._alert')
                <!-- Navbar -->
                @include('layouts.back.partials._navbar')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    <!-- / Content -->
                    <!-- Footer -->
                    @include('layouts.back.partials._footer')

                    <form method="POST" id="destroy" style="display: none">
                        <input type="hidden" name="_token" value="<?php echo csrf_token()?>">
                        <input type="hidden" name="_method" value="DELETE">
                    </form>
                    <!-- / Footer -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
    @include('layouts.back.partials._scripts')

    <script>
        function deleteData(url)
        {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (result.isConfirmed) {
                    $("#destroy").attr('action', url);
                    $("#destroy").submit();
                }
            })
        }
    </script>

    @stack('scripts')
</body>

</html>
