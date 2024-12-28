<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../System/assets/"
    data-template="vertical-menu-template-starter">
    <head>
        @include("System.layouts.partials.up")

        <!-- Page -->
        <link rel="stylesheet" href="{{ asset('System/assets/vendor/css/pages/page-auth.css') }}" />
    </head>
    <body>
        <div class="authentication-wrapper authentication-cover">
            <div class="authentication-inner row m-0">
                <div class="d-none d-lg-flex col-lg-8 p-0">
                    <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="{{ asset('System/assets/img/utils/login/background.png') }}" alt="auth-login-cover" class="my-5 img-fluid w-50">
                    </div>
                </div>
                <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-12 p-6">
                    <div class="w-px-400 mx-auto mt-12 pt-5">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        @include("System.layouts.partials.down")
    </body>
</html>
