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
                                <img src="{{ asset('storage/'.$company->logotype) }}" class="img-fluid"/>
                            </span>
                            <span class="app-brand-text demo menu-text fw-bold ms-2 ps-1 fw-regular">{{ $company->commercial_name }}</span>
                        </a>
                    </div>
                    <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                        <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="ti ti-x ti-sm"></i>
                        </button>
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link fw-medium" href="home">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-medium" href="book_complaints">Libro de reclamaciones y sugerencias</a>
                            </li>
                        </ul>
                    </div>
                    <div class="landing-menu-overlay d-lg-none"></div>
                </div>
            </div>
        </nav>
        <div data-bs-spy="scroll" class="scrollspy-example">
            {{-- <section id="hero-animation">
                <div id="landingHero" class="section-py landing-hero position-relative">
                    <div class="container">
                        <div class="hero-text-box text-center">
                            <h1 class="text-primary hero-title display-6 fw-bold">{{ $company->commercial_name }}</h1>
                            <h2 class="hero-sub-title h6 mb-4 pb-1">{{ $company->description }}</h2>
                        </div>
                    </div>
                </div>
                <div class="landing-hero-blank"></div>
            </section> --}}
            <div>
                @yield('content')
                <div class="content-backdrop fade"></div>
            </div>
        </div>

        @include("Guest.layouts.partials.down")
    </body>
</html>
