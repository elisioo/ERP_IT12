<div class="sidebar-header">
    <a href="{{ route('employee.dashboard') }}"
        class="d-flex align-items-center mb-3 mb-md-0 me-md-auto ps-2 text-decoration-none">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/kdr.png') }}" alt="Korean Diner Logo" style="width: 40px; height: 40px;" class="me-3">
            <div style="line-height: 1.1;" class="sidebar-text">
                <span class="d-block fs-5 fw-bold text-dark">Korean Diner</span>
                <small class="text-muted" style="font-size: 0.9rem;">Davao</small>
            </div>
        </div>
    </a>
</div>

<hr class="mt-3 mb-3">

<ul class="nav nav-pills flex-column mb-auto">
    <li>
        <a href="{{ route('menu') }}" 
           class="nav-link"
           data-bs-toggle="tooltip" 
           data-bs-placement="right" 
           title="Back to Menu">
            <i class="fa-solid fa-arrow-left me-2"></i> 
            <span class="sidebar-text">Back to Menu</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
        <a href="{{ route('employee.dashboard') }}"
           class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}"
           data-bs-toggle="tooltip" 
           data-bs-placement="right" 
           title="Dashboard">
            <i class="fa-solid fa-chart-line me-2"></i> 
            <span class="sidebar-text">Dashboard</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('attendance.*') ? 'active' : '' }}">
        <a href="{{ route('attendance.index') }}"
           class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}"
           data-bs-toggle="tooltip" 
           data-bs-placement="right" 
           title="Attendance">
            <i class="fa-solid fa-clock me-2"></i> 
            <span class="sidebar-text">Attendance</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('employee.payroll') ? 'active' : '' }}">
        <a href="{{ route('employee.payroll') }}"
           class="nav-link {{ request()->routeIs('employee.payroll') ? 'active' : '' }}"
           data-bs-toggle="tooltip" 
           data-bs-placement="right" 
           title="Payroll">
            <i class="fa-solid fa-money-bill me-2"></i> 
            <span class="sidebar-text">Payroll</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <a href="{{ route('reports.index') }}" 
           class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
           data-bs-toggle="tooltip" 
           data-bs-placement="right" 
           title="Reports">
            <i class="fa-solid fa-chart-bar me-2"></i> 
            <span class="sidebar-text">Reports</span>
        </a>
    </li>
</ul>

<div class="sidebar-footer">
    <div class="dropdown mt-auto">
        <hr>
        <a href="#" 
           class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" 
           id="dropdownUser1"
           data-bs-toggle="dropdown" 
           aria-expanded="false"
           data-bs-placement="right" 
           title="{{ session('admin_username', 'Admin') }}">
            <img src="{{ session('admin_profile_picture') ? asset('storage/' . session('admin_profile_picture')) : 'https://i.pinimg.com/originals/45/de/42/45de424a29a8000a65787ec74440799c.png' }}"
                alt="" width="32" height="32" class="rounded-circle me-2" style="object-fit: cover;">
            <strong class="sidebar-text">{{ session('admin_username', 'Admin') }}</strong>
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
</div>

<style>
.nav-link {
    display: flex;
    align-items: center;
}

.sidebar-collapsed .nav-link {
    justify-content: center;
}

.sidebar-collapsed .nav-link i {
    margin: 0 !important;
}
</style>