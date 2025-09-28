<!-- Sidebar -->
<a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <span class="fs-4">
        <i class="fa-solid fa-bowl-food me-2"></i> Inventory
    </span>
</a>
<hr class="text-white mt-4">

<ul class="nav nav-pills flex-column mb-auto">
    <li class="{{ ($active ?? '') === 'dashboard' ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}"
            class="nav-link {{ ($active ?? '') === 'dashboard' ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-chart-line me-2"></i> Dashboard
        </a>
    </li>

    <li class="{{ ($active ?? '') === 'orders' ? 'active' : '' }}">
        <a href="{{ route('orders') }}" class="nav-link {{ ($active ?? '') === 'orders' ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-receipt me-2"></i> Orders
        </a>
    </li>

    <li class="{{ ($active ?? '') === 'products' ? 'active' : '' }}">
        <a href="#" class="nav-link {{ ($active ?? '') === 'products' ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-box-open me-2"></i> Products
        </a>
    </li>

    <li class="{{ ($active ?? '') === 'customers' ? 'active' : '' }}">
        <a href="#" class="nav-link {{ ($active ?? '') === 'customers' ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-users me-2"></i> Customers
        </a>
    </li>
</ul>





<!-- Dropdown at bottom -->
<div class="dropdown mt-auto">
    <hr class="text-white">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1"
        data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong>Admin</strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
        <li><a class="dropdown-item" href="#">New project...</a></li>
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="#">Sign out</a></li>
    </ul>
</div>

<style>
.active {
    background-color: #ffffffff !important;
    color: #212529 !important;
}
</style>