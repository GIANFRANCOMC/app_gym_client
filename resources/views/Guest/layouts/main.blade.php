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
        </script>
    </head>
    <body style="background-color: #f8f7fa;">
        <nav class="layout-navbar shadow-none py-0">
            <div class="container">
                <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-4 bg-white mt-3 shadow-sm">
                    <div class="navbar-brand app-brand demo d-flex py-0 py-lg-2 me-4">
                        <button class="navbar-toggler border-0 px-0 me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="ti ti-menu-2 ti-sm align-middle"></i>
                        </button>
                        <a href="javascript:void(0)" class="app-brand-link">
                            <span class="app-brand-logo demo">
                                <img src="{{ asset('storage/'.$company->logotype) }}" class="img-fluid w-100"/>
                            </span>
                            <span class="app-brand-text demo menu-text ms-2 ps-1 fw-semibold text-dark">{{ $company->commercial_name }}</span>
                        </a>
                    </div>
                    <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                        <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="ti ti-x ti-sm"></i>
                        </button>
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="home">
                                    <i class="fa fa-home"></i>
                                    <span class="ms-1">Inicio</span>
                                </a>
                            </li>
                            <li class="nav-item mega-dropdown ms-1">
                                <a href="javascript:void(0);" class="nav-link dropdown-toggle navbar-ex-14-mega-dropdown mega-dropdown" aria-expanded="false" data-bs-toggle="mega-dropdown" data-trigger="hover">
                                    <i class="fa fa-headset"></i>
                                    <span class="ms-1">Servicio al cliente</span>
                                </a>
                                <div class="dropdown-menu p-4 p-xl-8">
                                    <div class="row gy-4">
                                        <div class="col-12 col-lg">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <a class="nav-link mega-dropdown-link" href="book_complaints">
                                                        <span>Libro de reclamaciones y sugerencias</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-4 d-none d-lg-block">
                                            <div class="bg-body nav-img-col p-2">
                                                <img src="{{ asset('storage/'.$company->logotype) }}" alt="Logo" class="w-100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li>
                                <a href="https://{{ request()->getHost() }}?company={{ base64_encode($company->id) }}" class="btn btn-info-1 btn-sm rounded-pill shadow-sm waves-effect waves-light">
                                    <i class="fa fa-globe"></i>
                                    <span class="ms-2">Ingresar a mi plataforma</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="landing-menu-overlay d-lg-none"></div>
                </div>
            </div>
        </nav>
        <div data-bs-spy="scroll" class="scrollspy-example">
            <div>
                @yield('content')
                <div class="content-backdrop fade"></div>
            </div>
        </div>

        @include("Guest.layouts.partials.down")
    </body>
</html>
