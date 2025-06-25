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
    </head>
    <style>
        body {
            background-color: #f8f6fb;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23e0dce4' fill-opacity='0.18' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
    </style>
    @php
        $ownerApp     = config("app.owner_app");
        $hasCompany   = isset($data->company) && !empty($data->company);
        $hasCompanies = isset($data->companies) && !empty($data->companies) && count($data->companies) > 0;
    @endphp
    <body>
        <div class="container-fluid min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="row w-100 shadow-lg rounded overflow-hidden" style="max-width: 960px;">
                <div class="col-lg-6 d-none d-lg-flex bg-light align-items-center justify-content-center p-3">
                    <img src="{{ asset($ownerApp->assets->img->login_image) }}" class="img-fluid" style="max-height: 400px;">
                </div>
                <div class="col-12 col-lg-6 bg-white px-3 px-md-5 py-4 py-md-5">
                    <div class="border-0">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            <div class="text-center mt-3 mt-md-4">
                <small class="text-muted">
                    ¿Necesitas ayuda? Escríbenos ✉️
                    <a href="mailto:{{ $ownerApp->support->email }}" class="text-primary">
                        {{ $ownerApp->support->email }}
                    </a>
                    o llámanos 📞
                    <a href="tel:{{ $ownerApp->support->phone }}" class="text-primary">
                        {{ $ownerApp->support->phone }}
                    </a>
                </small>
            </div>
        </div>

        @include("System.layouts.partials.down")
    </body>
</html>
