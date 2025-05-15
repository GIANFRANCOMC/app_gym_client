<!DOCTYPE html>

@php
    $user     = Auth::user();
    $company  = $user->company;
    $role     = $user->role;
    $sections = Cache::get("active_sections_{$company->id}");
    $ownerApp = config("app.owner_app");

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
        <script>
            window.ownerApp = @json($ownerApp);
            window.company = @json($company);
        </script>
    </head>
    <body>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                    <ul class="menu-inner my-3">
                        <li class="menu-header mb-3">
                            <span class="menu-header-text" data-i18n="Profile">
                                <div class="row">
                                    <div class="col col-auto">
                                        <div class="avatar avatar-online mt-2">
                                            <span class="avatar-initial rounded-circle bg-label-dark">
                                                <img src="{{ asset($ownerApp->assets->img->logomark) }}"/>
                                            </span>
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
                    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                <i class="ti ti-menu-2 ti-sm"></i>
                            </a>
                        </div>
                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                            <div class="navbar-nav align-items-center">
                                <div class="nav-item navbar-search-wrapper mb-0">
                                    <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="{{ $ownerApp->web }}" target="_blank">
                                        <img src="{{ asset($ownerApp->assets->img->logotype) }}" class="d-none d-md-block" width="130" height="38" alt="Logo" />
                                        <img src="{{ asset($ownerApp->assets->img->logomark) }}" class="d-block d-md-none" width="55" height="45" alt="Logo" />
                                    </a>
                                </div>
                            </div>
                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <li class="nav-item">
                                    <a class="nav-link px-0" href="javascript:void(0);" onclick='generateMyUrl(@json($company), true, "my_web")'>
                                        <div class="btn btn-success btn-sm rounded-pill shadow-sm">
                                            <i class="fa fa-globe"></i>
                                            <span class="ms-2">Visitar mi página</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <div class="content-wrapper">
                        <div class="container-xxl flex-grow-1 container-p-y">
                            @yield('content')
                        </div>
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
