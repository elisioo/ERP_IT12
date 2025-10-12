<a href="{{ route('employee.dashboard') }}"
    class="d-flex align-items-center mb-3 mb-md-0 me-md-auto ps-2 text-decoration-none">
    <div class="d-flex align-items-center">
        <img src="{{ asset('img/kdr.png') }}" alt="Korean Diner Logo" style="width: 40px; height: 40px;" class="me-3">
        <div style="line-height: 1.1;">
            <span class="d-block fs-5 fw-bold text-dark">Korean Diner</span>
            <small class="text-muted" style="font-size: 0.9rem;">Davao</small>
        </div>
    </div>
</a>

<hr class="mt-3 mb-3">

<ul class="nav nav-pills flex-column mb-auto">
    <li>
        <a href="{{ route('menu') }}" class="nav-link">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Menu
        </a>
    </li>

    <li class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
        <a href="{{ route('employee.dashboard') }}"
            class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line me-2"></i> Dashboard
        </a>
    </li>

    <li class="{{ request()->routeIs('attendance.*') ? 'active' : '' }}">
        <a href="{{ route('attendance.index') }}"
            class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
            <i class="fa-solid fa-clock me-2"></i> Attendance
        </a>
    </li>

    <li class="{{ request()->routeIs('employee.payroll') ? 'active' : '' }}">
        <a href="{{ route('employee.payroll') }}"
            class="nav-link {{ request()->routeIs('employee.payroll') ? 'active' : '' }}">
            <i class="fa-solid fa-money-bill me-2"></i> Payroll
        </a>
    </li>

    <li class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <a href="{{ route('reports.index') }}"
            class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-bar me-2"></i> Reports
        </a>
    </li>
</ul>

<div class="dropdown mt-auto">
    <hr>
    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1"
        data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ session('admin_profile_picture') ? asset('storage/' . session('admin_profile_picture')) : 'https://i.pinimg.com/originals/45/de/42/45de424a29a8000a65787ec74440799c.png' }}"
            alt="" width="32" height="32" class="rounded-circle me-2" style="object-fit: cover;">
        <strong>{{ session('admin_username', 'Admin') }}</strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">Settings</a></li>
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">Profile</a></li>
        <li><a class="dropdown-item" href="{{ route('dashboard.index') }}">Manage Inventory</a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item">Sign out</button>
            </form>
        </li>
    </ul>
</div>