<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../System/assets/"
  data-template="vertical-menu-template-starter">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ config('app.name', 'NombreSistema') }}</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('System/assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />

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
    <link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/animate-css/animate.css') }}" />

    <link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('System/assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('System/assets/css/custom.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('System/assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('System/assets/vendor/js/template-customizer.js') }}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('System/assets/js/config.js') }}"></script>
  </head>

    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Menu -->
                <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                    <div class="app-brand demo">
                        <a href="#" class="app-brand-link">
                            <span class="app-brand-logo demo">
                                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                                        fill="#7367F0" />
                                    <path
                                        opacity="0.06"
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                                        fill="#161616" />
                                    <path
                                        opacity="0.06"
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                                        fill="#161616" />
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                                        fill="#7367F0" />
                                </svg>
                            </span>
                            <div class="app-brand-text demo menu-text fw-bold">
                                {{ config('app.name', 'NombreSistema') }}
                            </div>
                        </a>

                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
                        </a>
                    </div>

                    <div class="menu-inner-shadow"></div>

                    <ul class="menu-inner py-1">
                        <li class="menu-item mt-3">
                            <a href="#" class="menu-link" id="menu-item-home">
                                <i class="fa fa-home me-2"></i>
                                <div data-i18n="Inicio">Inicio</div>
                            </a>
                        </li>
                        <li class="menu-item" id="menu-item-sales">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="fa-solid fa-cash-register me-2"></i>
                                <div data-i18n="Ventas">Ventas</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item" id="menu-item-list-sales">
                                    <a href="{{ route('sales.index') }}" class="menu-link">
                                        <div data-i18n="Collapsed menu">Listado</div>
                                    </a>
                                </li>
                                <li class="menu-item" id="menu-item-create-sales">
                                    <a href="{{ route('sales.create') }}" class="menu-link">
                                        <div data-i18n="Collapsed menu">Nuevo</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-item" id="menu-item-items">
                            <a href="{{ route('items.index') }}" class="menu-link">
                                <i class="fa fa-bottle-water me-2"></i>
                                <div data-i18n="Page 1">Productos - Servicios</div>
                            </a>
                        </li>
                        <li class="menu-item" id="menu-item-customers">
                            <a href="{{ route('customers.index') }}" class="menu-link">
                                <i class="fa fa-user me-2"></i>
                                <div data-i18n="Page 1">Clientes</div>
                            </a>
                        </li>
                        <li class="menu-item" id="menu-item-reports">
                            <a href="{{ route('users.index') }}" class="menu-link">
                                <i class="fa fa-print me-2"></i>
                                <div data-i18n="Page 1">Reportes</div>
                            </a>
                        </li>
                        <li class="menu-item" id="menu-item-configuration">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="fa fa-gear me-2"></i>
                                <div data-i18n="Configuración">Configuración</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item" id="menu-item-list-sales">
                                    <a href="{{ route('sales.index') }}" class="menu-link">
                                        <div data-i18n="Collapsed menu">Mi empresa</div>
                                    </a>
                                </li>
                                <li class="menu-item" id="menu-item-list-brands">
                                    <a href="{{ route('branches.index') }}" class="menu-link">
                                        <div data-i18n="Collapsed menu">Sucursales</div>
                                    </a>
                                </li>
                                <li class="menu-item" id="menu-item-list-users">
                                    <a href="{{ route('users.index') }}" class="menu-link">
                                        <div data-i18n="Collapsed menu">Colaboradores</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-item">
                            <a href="javascript:void(0)" class="menu-link bg-danger" onclick="$('#logout').submit();">
                                <i class="fa fa-right-from-bracket me-2"></i>
                                <div data-i18n="Page 2" class="text-white">Cerrar sesión</div>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" id="logout">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </aside>
                <div class="layout-page">
                    <nav
                        class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                        id="layout-navbar">
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="ti ti-menu-2 ti-sm"></i>
                            </a>
                        </div>
                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                            <div class="navbar-nav align-items-center">
                                <div class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <i class="ti ti-md"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-start dropdown-styles">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                                <span class="align-middle"><i class="ti ti-sun me-2"></i>Light</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                                <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                                                <span class="align-middle"><i class="ti ti-device-desktop me-2"></i>System</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <div class="avatar">
                                            <img src="{{ asset('System/assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle" />
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar">
                                                    <img src="{{ asset('System/assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-medium d-block">{{ Auth::user()->name }}</span>
                                                <small class="text-muted">{{ Auth::user()->name }}</small>
                                            </div>
                                        </div>
                                        </a>
                                    </li>
                                    {{-- <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                        <i class="ti ti-user-check me-2 ti-sm"></i>
                                        <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                        <i class="ti ti-settings me-2 ti-sm"></i>
                                        <span class="align-middle">Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                        <span class="d-flex align-items-center align-middle">
                                            <i class="flex-shrink-0 ti ti-credit-card me-2 ti-sm"></i>
                                            <span class="flex-grow-1 align-middle">Billing</span>
                                            <span class="flex-shrink-0 badge badge-center rounded-pill bg-label-danger w-px-20 h-px-20"
                                            >2</span
                                            >
                                        </span>
                                        </a>
                                    </li> --}}
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="$('#logout').submit();">
                                        <i class="ti ti-logout me-2 ti-sm"></i>
                                        <span class="align-middle">Cerrar sesión</span>
                                        </a>
                                    </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>

                    <div class="content-wrapper">
                        <div class="container-xxl flex-grow-1 container-p-y">
                            @yield('content')
                        </div>
                        <footer class="content-footer footer bg-footer-theme">
                            <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
                                <div>
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                , Hecho por <a href="https://pixinvent.com" target="_blank" class="fw-medium">Gianfranco MC</a>
                                </div>
                                <div class="d-none d-lg-inline-block">
                                <a
                                    href="https://demos.pixinvent.com/vuexy-html-admin-template/documentation/"
                                    target="_blank"
                                    class="footer-link me-4">Documentación</a
                                >
                                </div>
                            </div>
                            </div>
                        </footer>
                        <div class="content-backdrop fade"></div>
                    </div>
                </div>
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>

            <!-- Drag Target Area To SlideIn Menu On Small Screens -->
            <div class="drag-target"></div>
        </div>
        <!-- / Layout wrapper -->

        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->
        <script src="{{ asset('System/assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('System/assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('System/assets/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('System/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
        <script src="{{ asset('System/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('System/assets/vendor/libs/hammer/hammer.js') }}"></script>
        <script src="{{ asset('System/assets/vendor/js/menu.js') }}"></script>
        <!-- endbuild -->

        <script src="{{ asset('System/assets/vendor/libs/select2/select2.js') }}"></script>
        <script src="{{ asset('System/assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

        <!-- Vendors JS -->
        <script src="{{ asset('System/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
        <script src="{{ asset('System/assets/js/ui-toasts.js') }}"></script>
        <script src="{{ asset('System/assets/vendor/libs/toastr/toastr.js') }}"></script>

        <!-- Main JS -->
        <script src="{{ asset('System/assets/js/main.js') }}"></script>
        <!-- Page JS -->
    </body>
</html>
