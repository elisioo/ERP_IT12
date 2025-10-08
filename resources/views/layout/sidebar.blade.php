<!-- Sidebar -->
<a href="{{ route('dashboard') }}"
    class="d-flex align-items-center mb-3 mb-md-0 me-md-auto ps-2 text-white text-decoration-none">
    <div class="d-flex align-items-center">
        <img src="{{ asset('img/kdr.png') }}" alt="Korean Diner Logo" style="width: 40px; height: 40px;" class="me-3">
        <div style="line-height: 1.1;">
            <span class="d-block fs-5 fw-bold">Korean Diner</span>
            <small class="text-white-50" style="font-size: 0.9rem;">Davao</small>
        </div>
    </div>
</a>

<hr class="text-white mt-3 mb-3">

<ul class="nav nav-pills flex-column mb-auto">
    <li class="{{ ($active ?? '') === 'dashboard' ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}"
            class="nav-link {{ ($active ?? '') === 'dashboard' ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-chart-line me-2"></i> Dashboard
        </a>
    </li>

    <li class="{{ ($active ?? '') === 'orders' ? 'active' : '' }}">
        <a href="{{ route('orders.index') }}"
            class="nav-link {{ ($active ?? '') === 'orders' ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-receipt me-2"></i> Orders
        </a>
    </li>

    <li class="{{ ($active ?? '') === 'menus' ? 'active' : '' }}">
        <a href="{{ route('menus.index') }}"
            class="nav-link {{ ($active ?? '') === 'menus' ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-clipboard-list me-2"></i> Menu
        </a>
    </li>
    <li class="{{ ($active ?? '') === 'expenses' ? 'active' : '' }}">
        <a href="{{ route('expenses.index') }}"
            class="nav-link {{ ($active ?? '') === 'expenses' ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-sack-dollar me-2"></i> Expenses
        </a>
    </li>
    <li class="{{ ($active ?? '') === 'category' ? 'active' : '' }}">
        <a href="#" class="nav-link {{ ($active ?? '') === 'category' ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-layer-group me-2  "></i> Category
        </a>
    </li>
    <li class="{{ ($active ?? '') === 'inventory' ? 'active' : '' }}">
        <a href="{{ route('inventory') }}"
            class="nav-link {{ ($active ?? '') === 'inventory' ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-box-open me-2"></i> Inventory
        </a>
    </li>
</ul>





<!-- Dropdown at bottom -->
<div class="dropdown mt-auto">
    <hr class="text-white">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1"
        data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://i.pinimg.com/originals/45/de/42/45de424a29a8000a65787ec74440799c.png" alt="" width="32"
            height="32" class="rounded-circle me-2">
        <strong>Admin</strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><a class="dropdown-item" href="#">Profile</a></li>

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

<style>
.active {
    background-color: #ffffffff !important;
    color: #212529 !important;
    border-radius: 0.375rem !important;
}
</style>