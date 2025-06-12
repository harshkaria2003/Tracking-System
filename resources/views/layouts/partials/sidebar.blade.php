<nav class="sidebar p-3" aria-label="Sidebar Navigation">
    <h4 class="text-white mb-4">
        <i class="fas fa-cogs me-2"></i> KaiAdmin
    </h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>

        @php $roleId = auth()->check() ? auth()->user()->role_id : null; @endphp

        @if ($roleId == 1)
            {{-- Super Admin: Add Admin --}}
            <li class="nav-item mb-2">
                <button type="button" class="btn btn-link nav-link" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                    <i class="fas fa-user-shield me-2"></i> Add Admin
                </button>
            </li>

            {{-- Employee Report --}}
            <li class="nav-item {{ request()->is('employee/report') ? 'active' : '' }}">
                <a href="{{ url('/employee/report') }}" class="nav-link">
                    <i class="fas fa-file-alt me-2"></i> Employee Report
                </a>
            </li>

            {{-- Time Log Report --}}
            <li class="nav-item {{ request()->is('timelog/report') ? 'active' : '' }}">
                <a href="{{ url('/timelog/report') }}" class="nav-link">
                    <i class="fas fa-chart-line me-2"></i> Time Log Report
                </a>
            </li>

        @elseif ($roleId == 2)
            {{-- Admin: Add Employee --}}
            <li class="nav-item mb-2">
                <button type="button" class="btn btn-link nav-link" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="fas fa-user-plus me-2"></i> Add Employee
                </button>
            </li>

           {{-- Hours Summary Report (Admin only) --}}
<li class="nav-item {{ request()->is('employee/hours-summary') ? 'active' : '' }}">
    <a href="{{ route('admin.admin.employee.report') }}" class="nav-link">
        <i class="fas fa-clock me-2"></i> Hours Summary Report
    </a>
</li>


            

            {{-- Time Log Report --}}
            <li class="nav-item {{ request()->is('timelog/report') ? 'active' : '' }}">
               <a href="{{ route('admin.timelog.report') }}" class="btn btn-primary">View all records </a>
            </li>
        @elseif ($roleId == 3)
            {{-- Employee: Time Log Report --}}
            <li class="nav-item {{ request()->is('timelog/report') ? 'active' : '' }}">
                <a href="{{ route('employee.timelog.report') }}" class="nav-link">
                    <i class="fas fa-chart-line me-2"></i> Time Log Report
                </a>
            </li>
        @endif

        <li class="nav-item mt-4">
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Are you sure you want to logout?')">
                @csrf
                <button type="submit" class="btn btn-danger w-100 text-start">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</nav>
