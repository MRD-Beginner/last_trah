@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    $containerNav = $containerNav ?? 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';
@endphp

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
    @keyframes wave {
        0% {
            transform: rotate(0deg);
        }

        10% {
            transform: rotate(16deg);
        }

        20% {
            transform: rotate(-6deg);
        }

        30% {
            transform: rotate(16deg);
        }

        40% {
            transform: rotate(-4deg);
        }

        50% {
            transform: rotate(16deg);
        }

        60% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(0deg);
        }
    }

    .waving-hand {
        animation: wave 2.1s 0.6s infinite;
        transform-origin: 75% 75%;
        display: inline-block;
        font-size: 25px !important;
    }
</style>

<!-- Navbar -->
@if (isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    <nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme"
        id="layout-navbar">
@endif
@if (isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="{{ $containerNav }}">
@endif

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">@include('_partials.macros', ['width' => 25, 'withbg' => 'var(--bs-primary)'])</span>
            <span
                class="app-brand-text demo menu-text fw-bold text-heading">{{ config('variables.templateName') }}</span>
        </a>
    </div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
    <div
        class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="bx bx-menu bx-md"></i>
        </a>
    </div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <!-- Search -->
    <div class="navbar-nav align-items-center">
        <div class="nav-item d-flex align-items-center">
            <div class="fw-normal">Hello <span class="fw-medium">{{ auth()->user()->name }}</span> <span
                    class="waving-hand">👋</span></div>
        </div>
    </div>
    <!-- /Search -->
    <ul class="navbar-nav flex-row align-items-center ms-auto">
        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                    <img src="{{ asset('assets/img/avatars/free-user-icon-3296-thumb.png') }}" alt class="w-px-40 h-auto rounded-circle">
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="javascript:void(0);">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    <img src="{{ asset('assets/img/avatars/free-user-icon-3296-thumb.png') }}" alt
                                        class="w-px-40 h-auto rounded-circle">
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                <small class="text-muted">{{ auth()->user()->role }}</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider my-1"></div>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('user.profile', ['id' => auth()->id()]) }}">
                        <i class="bx bx-user me-2"></i>
                        <span>My Profile</span>
                    </a>
                </li>
                <li>
                  @if(auth()->user()->role == 'admin')
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.keluarga') }}">
                        <i class="bx bx-group me-2"></i>
                        <span>Data Keluarga</span>
                    </a>
                  @else
                   <a class="dropdown-item d-flex align-items-center" href="{{ route('user.keluarga') }}">
                        <i class="bx bx-group me-2"></i>
                        <span>Data Keluarga</span>
                    </a>
                  @endif
                </li>
                @if(auth()->user()->role == 'admin')
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.user.management') }}">
                            <i class="bx bx-cog me-2"></i>
                            <span>Data User</span>
                        </a>
                    </li>
                @endif
                <div class="dropdown-divider my-1"></div>
        </li>
        <li>
            <!-- Di dalam dropdown menu Sneat -->
            <a class="dropdown-item" href="#"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bx bx-power-off me-2"></i>
                <span>Log Out</span>
            </a>

            <!-- Form logout tersembunyi -->
            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
    </li>
    <!--/ User -->
    </ul>
</div>

@if (!isset($navbarDetached))
    </div>
@endif
</nav>
<!-- / Navbar -->
