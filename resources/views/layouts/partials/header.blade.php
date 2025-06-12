<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'KaiAdmin Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- FontAwesome -->
    <link href="{{ asset('admin/assets/css/fontawesome.min.css') }}" rel="stylesheet" />

    <!-- KaiAdmin CSS -->
    <link href="{{ asset('admin/assets/css/kaiadmin.css') }}" rel="stylesheet" />

    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="#" class="nav-link" data-widget="pushmenu" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/') }}" class="nav-link">Home</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- User Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a id="userDropdown" href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a
                            class="dropdown-item"
                            href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        >
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Panel -->
        <div class="main-panel">
            <div class="content">
                @yield('content')
            </div>

            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </div>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- FontAwesome JS -->
    <script src="{{ asset('admin/assets/js/fontawesome.min.js') }}"></script>

    <!-- KaiAdmin Custom JS -->
    <script src="{{ asset('admin/assets/js/kaiadmin.js') }}"></script>

    @stack('scripts')
</body>
</html>
