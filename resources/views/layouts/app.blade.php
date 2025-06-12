<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') | KaiAdmin</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
<!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <style>
        body { background-color: #f4f6f9; }
        .sidebar { width: 250px; min-height: 100vh; background-color: #343a40; transition: width 0.3s ease; }
        .sidebar a, .sidebar button.btn-link { color: #fff; text-align: left; width: 100%; }
        .sidebar a.nav-link.active { background-color: #198754; }
        .flex-grow-1 {
            transition: margin-left 0.3s ease;
            margin-left: 250px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .sidebar-collapsed .sidebar { width: 0 !important; overflow: hidden; }
        .sidebar-collapsed .flex-grow-1 { margin-left: 0 !important; }
    </style>

    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="{{ session('sidebar_collapsed') ? 'sidebar-collapsed' : '' }}">
<div class="d-flex">
    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Page Content --}}
    <div class="flex-grow-1">
        {{-- Top Navbar --}}
        <nav class="navbar navbar-expand navbar-light bg-white shadow-sm px-3">
            <div class="container-fluid justify-content-between">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary me-2" id="sidebarToggle" aria-label="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="navbar-brand mb-0 h5">@yield('title')</span>
                </div>

                <div class="d-flex align-items-center">
                    {{-- Timer with Start/Stop --}}
                    <div class="d-flex align-items-center me-3">
                        <span id="globalTimer" class="me-2" style="font-weight: 600; font-size: 1.25rem; font-family: monospace;">00:00:00</span>
                        <button id="timerToggleBtn" class="btn btn-sm btn-success" style="font-weight: bold;">
                            <i class="fas fa-play"></i> Start
                        </button>
                    </div>

                    @auth
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            <span>
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                <small class="text-muted">({{
                                    Auth::user()->role_id == 1 ? 'Super Admin' :
                                    (Auth::user()->role_id == 2 ? 'Admin' : 'Employee')
                                }})</small>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-id-badge me-2"></i> View Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="content-wrapper px-3 py-4">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="text-center py-3 text-muted small bg-light mt-auto">
            &copy; {{ date('Y') }} KaiAdmin. All rights reserved.
        </footer>
    </div>
</div>

{{-- Modals --}}
@if (Auth::check() && Auth::user()->role_id == 1)
    @include('models.add_admin_modal')
@elseif (Auth::check() && Auth::user()->role_id == 2)
    @include('models.add_employee_modal')
@endif

{{-- Scripts --}}
<script src="{{ asset('admin/assets/js/core/popper.min.js') }}" defer></script>
<script src="{{ asset('admin/assets/js/core/jquery-3.7.1.min.js') }}" defer></script>
<script src="{{ asset('admin/assets/js/kaiadmin.min.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const sidebarToggle = document.getElementById('sidebarToggle');

    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        body.classList.add('sidebar-collapsed');
    }

    sidebarToggle?.addEventListener('click', () => {
        body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebar-collapsed', body.classList.contains('sidebar-collapsed'));
    });

    
    let timerInterval = null;
    let elapsedSeconds = 0;
    const timerDisplay = document.getElementById('globalTimer');
    const toggleBtn = document.getElementById('timerToggleBtn');

    function formatDuration(seconds) {
        const h = Math.floor(seconds / 3600).toString().padStart(2, '0');
        const m = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
        const s = (seconds % 60).toString().padStart(2, '0');
        return `${h}:${m}:${s}`;
    }

    function updateTimerDisplay() {
        timerDisplay.textContent = formatDuration(elapsedSeconds);
    }

    function startTimer() {
        const startTime = Date.now();
        timerInterval = setInterval(() => {
            elapsedSeconds = Math.floor((Date.now() - startTime) / 1000);
            updateTimerDisplay();
        }, 1000);
    }

    function stopTimer() {
        clearInterval(timerInterval);
        elapsedSeconds = 0;
        updateTimerDisplay();
    }

    toggleBtn?.addEventListener('click', () => {
        if (toggleBtn.classList.contains('btn-success')) {
            elapsedSeconds = 0;
            updateTimerDisplay();
            startTimer();
            toggleBtn.classList.remove('btn-success');
            toggleBtn.classList.add('btn-danger');
            toggleBtn.innerHTML = '<i class="fas fa-stop"></i> Stop';
        } else {
            stopTimer();
            toggleBtn.classList.remove('btn-danger');
            toggleBtn.classList.add('btn-success');
            toggleBtn.innerHTML = '<i class="fas fa-play"></i> Start';
        }
    });
});
</script>

@stack('scripts')
<script>
    (function () {
        let clickCount = 0;
        let scrollCount = 0;
        let keyPressCount = 0;
        const sendInterval = 5000;
        const trackUrl = "{{ url('/user-tracking/update-count') }}";
        const eventUrl = "{{ url('/user-tracking/store') }}";

        document.addEventListener('click', () => clickCount++);
        document.addEventListener('scroll', () => scrollCount++);
        document.addEventListener('keypress', () => keyPressCount++);

        setInterval(() => {
            if (clickCount || scrollCount || keyPressCount) {
                sendTrackingData();
            }
        }, sendInterval);

        window.addEventListener('beforeunload', () => {
            if (clickCount || scrollCount || keyPressCount) {
                navigator.sendBeacon(trackUrl, toFormData({
                    click: clickCount,
                    scroll: scrollCount,
                    keypress: keyPressCount,
                    url: window.location.href
                }));
            }
        });

        function sendTrackingData() {
            fetch(trackUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    click: clickCount,
                    scroll: scrollCount,
                    keypress: keyPressCount,
                    url: window.location.href
                })
            }).then(() => {
                clickCount = scrollCount = keyPressCount = 0;
            }).catch(console.error);
        }

        function toFormData(data) {
            const formData = new FormData();
            for (const key in data) {
                formData.append(key, data[key]);
            }
            return formData;
        }

       
        fetch(eventUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                event_type: 'page_view',
                event_data: 'Visited: ' + document.title,
                url: window.location.href
            })
        });
    })();
</script>

</body>
</html>
