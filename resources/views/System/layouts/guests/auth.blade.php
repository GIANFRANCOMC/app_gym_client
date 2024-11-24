<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content=""/>

        <title>{{ config('app.name', 'NombreSistema') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('System/assets/img/favicon/favicon.ico') }}"/>

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
        <div class="authentication-wrapper authentication-cover">
            <div class="authentication-inner row m-0">
                <div class="d-none d-lg-flex col-lg-8 p-0">
                    <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="{{ asset('System/assets/img/utils/login/background.png') }}" alt="auth-login-cover" class="my-5 img-fluid">
                    </div>
                </div>
                <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-12 p-6">
                    <div class="w-px-400 mx-auto mt-12 pt-5">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
