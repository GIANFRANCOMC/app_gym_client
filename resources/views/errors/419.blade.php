<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content=""/>

        <title>{{ config('app.name', 'NombreSistema') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('System/assets/img/utils/admin/4.png') }}"/>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com"/>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet"/>

        <!-- Icons -->
        <link rel="stylesheet" href="{{ asset('System/assets/vendor/fonts/tabler-icons.css') }}" />
        <link rel="stylesheet" href="{{ asset('System/assets/vendor/fonts/fontawesome.css') }}" />
        <link rel="stylesheet" href="{{ asset('System/assets/vendor/fonts/flag-icons.css') }}" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="{{ asset('System/assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ asset('System/assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{ asset('System/assets/css/demo.css') }}" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/node-waves/node-waves.css') }}" />
        <link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

        <!-- Page -->
        <link rel="stylesheet" href="{{ asset('System/assets/vendor/css/pages/page-auth.css') }}" />
        <link rel="stylesheet" href="{{ asset('System/assets/css/custom.css') }}" />
    </head>
    <body>
        <div class="container-xxl container-p-y">
            <div class="d-flex justify-content-center align-items-center">
                <div class="text-center">
                    <h1 class="mb-2 mx-2" style="line-height: 6rem;font-size: 6rem;">419</h1>
                    <div class="d-block fw-bold fs-4">
                        <i class="fa fa-warning text-warning"></i>
                        <span class="ms-1">Page expired</span>
                    </div>
                    <a href="{{ url('/login') }}" class="btn btn-primary mb-10 waves-effect">
                        <i class="fa fa-sign-in"></i>
                        <span class="ms-1">Ir al inicio</span>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
