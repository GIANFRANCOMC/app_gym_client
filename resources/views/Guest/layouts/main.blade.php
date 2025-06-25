<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-navbar-fixed layout-wide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../System/assets/"
    data-template="front-pages-no-customizer">
    <head>
        @include('Guest.layouts.partials.up')
        <script>
            window.company = @json($company);
            window.branch = @json($branch ?? null);
            window.withMenu = {{ json_encode($withMenu ?? true) }};
        </script>
    </head>
    <style>
        .bg-down-color-primary {
            background-color: #212121 !important;
        }

        .bg-down-color-secondary {
            background-color: #2c2c2c !important;
        }

        .bg-primary-custom {
            background-color: #ba60d7 !important;
        }

        .text-primary-custom {
            color: #ba60d7 !important;
        }

        .a-primary-custom {
            transition: color 0.3s ease-in-out;;
        }

        .a-primary-custom:hover {
            color: #ba60d7 !important;
        }

        .text-white-custom {
            color: #f4f4f4 !important;
        }
    </style>
    <body class="bg-down-color-secondary">
        @if($withMenu ?? true)
            <nav class="layout-navbar shadow-none py-0">
                <div class="container">
                    <div class="navbar navbar-expand-lg px-3 px-md-4 py-2 py-md-3 mt-3 shadow-sm bg-down-color-primary">
                        <div class="navbar-brand app-brand">
                            <button class="navbar-toggler text-white-custom me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="ti ti-menu-2 ti-sm align-middle"></i>
                            </button>
                            <a href="javascript:void(0)" class="app-brand-link">
                                <span class="app-brand-logo demo">
                                    <img src="{{ asset('storage/'.$company->logotype) }}" class="img-fluid w-100"/>
                                </span>
                            </a>
                        </div>
                        <div class="collapse navbar-collapse landing-nav-menu" id="navbarMenu">
                            <button class="navbar-toggler border-0 position-absolute end-0 top-0 text-white-custom" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fa fa-times fs-3"></i>
                            </button>
                            <ul class="navbar-nav me-auto d-flex gap-4 ms-3">
                                <li class="nav-item">
                                    <a class="fw-semibold fs-5 text-white-custom a-primary-custom" href="home">
                                        <span class="text-uppercase">Inicio</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="fw-semibold fs-5 text-white-custom a-primary-custom" href="book_complaints">
                                        <span class="text-uppercase">Libro de reclamaciones y sugerencias</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                {{--  --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        @endif
        <div data-bs-spy="scroll" class="scrollspy-example">
            <div>
                @yield('content')
                <div class="content-backdrop fade"></div>
            </div>
        </div>

        @include("Guest.layouts.partials.down")
    </body>
</html>
