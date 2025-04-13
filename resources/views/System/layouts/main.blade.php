<!DOCTYPE html>

@php
    $user     = Auth::user();
    $company  = $user->company;
    $role     = $user->role;
    $sections = Cache::get("active_sections_{$company->id}");

    // Cache data
    $hasActiveSections  = Cache::get("has_active_sections_{$company->id}");
    $lastActiveSections = Cache::get("last_active_sections_{$company->id}");
@endphp

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
    <body>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                    <div class="app-brand demo">
                        <a href="#" class="app-brand-link">
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

                    <ul class="menu-inner py-1 mb-3">
                        <li class="menu-header mb-4">
                            <span class="menu-header-text" data-i18n="Profile">
                                <div class="row">
                                    <div class="col col-auto">
                                        <div class="avatar avatar-online mt-2">
                                            <span class="avatar-initial rounded-circle bg-label-success">{{ substr($user->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="col col-auto">
                                        <span class="d-block fw-bold text-break">{{ Str::limit($user->name, 16) }}</span>
                                        <small class="d-block fw-semibold">{{ Str::limit($company->commercial_name, 16) }}</small>
                                        <small class="d-block fw-semibold">{{ !empty($role) ? $role->name : "" }}</small>
                                    </div>
                                </div>
                            </span>
                        </li>
                        @foreach($sections as $sec)
                            @php
                                $section = $sec["section"];
                                $subSec  = $sec["subSections"]->first();
                            @endphp
                            <li class="menu-item" id="{{ $section->dom_id }}">
                                <a href="{{ $section->has_sub_menu ? 'javascript:void(0);' : route($subSec->dom_route) }}" class="{{ $section->has_sub_menu ? 'menu-link menu-toggle' : 'menu-link' }}">
                                    <i class="{{ $section->dom_icon }} me-3"></i>
                                    <div>{{ $section->dom_label }}</div>
                                </a>
                                @if($section->has_sub_menu)
                                    <ul class="menu-sub">
                                        @foreach($sec["subSections"] as $subSection)
                                            <li class="menu-item" id="{{ $subSection->dom_id }}">
                                                <a href="{{ route($subSection->dom_route) }}" class="menu-link">
                                                    <div>{{ $subSection->dom_label }}</div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                        <li class="menu-item d-none">
                            <a href="javascript:void(0)" class="menu-link">
                                <i class="fa fa-check me-3"></i>
                                <div class="text-white">{{ $hasActiveSections }}</div>
                            </a>
                        </li>
                        <li class="menu-item d-none">
                            <a href="javascript:void(0)" class="menu-link">
                                <i class="fa fa-eye me-3"></i>
                                <div class="text-white">{{ $lastActiveSections }}</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="javascript:void(0)" class="menu-link bg-danger" onclick="$('#logout').submit();">
                                <i class="fa fa-right-from-bracket me-3"></i>
                                <div class="text-white">Cerrar sesión</div>
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
                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <div class="avatar avatar-online">
                                            <span class="avatar-initial rounded-circle bg-label-success">{{ substr($user->name, 0, 2) }}</span>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar avatar-online">
                                                    <span class="avatar-initial rounded-circle bg-label-success">{{ substr($user->name, 0, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-bold d-block text-break">{{ Str::limit($user->name, 16) }}</span>
                                                <span class="fw-medium d-block">{{ Str::limit($company->commercial_name, 16) }}</span>
                                                <small class="text-muted">{{ !empty($role) ? $role->name : "" }}</small>
                                            </div>
                                        </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="$('#logout').submit();">
                                            <i class="ti ti-logout ti-sm me-2"></i>
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
                                , Hecho por <a href="javascript:void(0)" class="fw-medium">Gianfranco MC</a>
                                </div>
                                {{-- <div class="d-none d-lg-inline-block">
                                    <a href="https://demos.pixinvent.com/vuexy-html-admin-template/documentation/" target="_blank" class="footer-link me-4">Documentación</a>
                                </div> --}}
                            </div>
                            </div>
                        </footer>
                        <div class="content-backdrop fade"></div>
                    </div>
                </div>
            </div>
            <div class="layout-overlay layout-menu-toggle"></div>
            <div class="drag-target"></div>
        </div>

        @include("System.layouts.partials.down")
    </body>
</html>
