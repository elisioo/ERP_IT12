<!-- Sidebar -->
<a href="{{ route('dashboard.index') }}"
    class="d-flex align-items-center mb-3 mb-md-0 me-md-auto ps-2 text-white text-decoration-none">
    <div class="d-flex align-items-center">
        <img src="{{ asset('img/kdr.png') }}" alt="Korean Diner Logo" style="width: 40px; height: 40px;" class="me-3">
        <div style="line-height: 1.1;" class="sidebar-text">
            <span class="d-block fs-5 fw-bold">Korean Diner</span>
            <small class="text-white-50" style="font-size: 0.9rem;">Davao</small>
        </div>
    </div>
</a>

<hr class="text-white mt-3 mb-3">

<ul class="nav nav-pills flex-column mb-auto">
    <li class="{{ ($page ?? '') === 'dashboard' ? 'active' : '' }}">
        <a href="{{ route('dashboard.index') }}"
            class="nav-link {{ ($page ?? '') === 'dashboard' ? 'active' : 'text-white' }}"
            data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
            <i class="fa-solid fa-chart-line me-2"></i> 
            <span class="sidebar-text">Dashboard</span>
        </a>
    </li>

    <li class="{{ ($page ?? '') === 'orders' ? 'active' : '' }}">
        <a href="{{ route('orders.index') }}"
            class="nav-link {{ ($page ?? '') === 'orders' ? 'active' : 'text-white' }}"
            data-bs-toggle="tooltip" data-bs-placement="right" title="Orders">
            <i class="fa-solid fa-receipt me-2"></i> 
            <span class="sidebar-text">Orders</span>
        </a>
    </li>

    <li class="{{ ($page ?? '') === 'menus' ? 'active' : '' }}">
        <a href="{{ route('menus.index') }}" 
            class="nav-link {{ ($page ?? '') === 'menus' ? 'active' : 'text-white' }}"
            data-bs-toggle="tooltip" data-bs-placement="right" title="Menu">
            <i class="fa-solid fa-clipboard-list me-2"></i> 
            <span class="sidebar-text">Menu</span>
        </a>
    </li>

    <li class="{{ ($active ?? '') === 'expenses' ? 'active' : '' }}">
        <a href="{{ route('expenses.index') }}"
            class="nav-link {{ ($active ?? '') === 'expenses' ? 'active' : 'text-white' }}"
            data-bs-toggle="tooltip" data-bs-placement="right" title="Expenses">
            <i class="fa-solid fa-sack-dollar me-2"></i> 
            <span class="sidebar-text">Expenses</span>
        </a>
    </li>

    <li class="{{ ($active ?? '') === 'category' ? 'active' : '' }}">
        <a href="{{ route('categories.index') }}"
            class="nav-link {{ ($active ?? '') === 'category' ? 'active' : 'text-white' }}"
            data-bs-toggle="tooltip" data-bs-placement="right" title="Category">
            <i class="fa-solid fa-layer-group me-2"></i> 
            <span class="sidebar-text">Category</span>
        </a>
    </li>

    <li class="{{ ($active ?? '') === 'inventory' ? 'active' : '' }}">
        <a href="{{ route('inventory.index') }}"
            class="nav-link {{ ($active ?? '') === 'inventory' ? 'active' : 'text-white' }}"
            data-bs-toggle="tooltip" data-bs-placement="right" title="Inventory">
            <i class="fa-solid fa-box-open me-2"></i> 
            <span class="sidebar-text">Inventory</span>
        </a>
    </li>
</ul>

<!-- Dropdown at bottom -->
<div class="dropdown mt-auto">
    <hr class="text-white">
    <a href="#" class="d-flex align-items-center text-light text-decoration-none dropdown-toggle" id="dropdownUser1"
        data-bs-toggle="dropdown" aria-expanded="false"
        data-bs-toggle="tooltip" data-bs-placement="right" title="{{ session('admin_username', 'Admin') }}">
        <img src="{{ session('admin_profile_picture') ? asset('storage/' . session('admin_profile_picture')) : 'https://i.pinimg.com/originals/45/de/42/45de424a29a8000a65787ec74440799c.png' }}"
            alt="" width="32" height="32" class="rounded-circle me-2" style="object-fit: cover;">
        <strong class="sidebar-text">{{ session('admin_username', 'Admin') }}</strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">Profile</a></li>
        <li><a class="dropdown-item" href="/">Return</a></li>
        <li><a class="dropdown-item" href="{{ route('employee.dashboard') }}">Manage Employee</a></li>
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

<script>
// Initialize tooltips for collapsed sidebar
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.active {
    background-color: #ffffffff !important;
    color: #212529 !important;
    border-radius: 0.375rem !important;
}

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