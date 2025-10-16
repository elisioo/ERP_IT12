@extends('layout.inventory_app')

@section('content')

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5">Inventory</h5>
            <p class="text-muted mb-0">Monitor stock levels and manage your menu items efficiently.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="#" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#archiveModal">
                <i class="fa-solid fa-box-archive"></i> View Archived
            </a>
            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#restockModal">
                <i class="fa-solid fa-box-open"></i> Restock
            </a>
        </div>
    </div>
    @if($stockAlert)
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        <div>{{ $stockAlert }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    <!-- Alerts -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if (!empty($inventoryAlert))
        @php
            $alertType = $inventoryAlert['type'] ?? 'warning';
            $alertMessage = $inventoryAlert['message'] ?? $inventoryAlert;
            $bgClass = $alertType === 'success' ? 'text-bg-success' : ($alertType === 'danger' ? 'text-bg-danger' : 'text-bg-warning');
            $icon = $alertType === 'success' ? 'fa-circle-check' : ($alertType === 'danger' ? 'fa-triangle-exclamation' : 'fa-boxes-stacked');
        @endphp
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
            <div id="inventoryToast" class="toast align-items-center {{ $bgClass }} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fa-solid {{ $icon }} me-2"></i>
                        {{ $alertMessage }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toastEl = document.getElementById('inventoryToast');
                const toast = new bootstrap.Toast(toastEl, { delay: 10000 });
                toast.show();
            });
        </script>
    @endif


    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-boxes fa-2x text-primary me-3"></i>
                    <div>
                        <h6 class="text-muted mb-2">Total Items</h6>
                        <h4 class="fw-bold">{{ $totalItems }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-exclamation-triangle fa-2x text-warning me-3"></i>
                    <div>
                        <h6 class="text-muted mb-2">Low Stock</h6>
                        <h4 class="fw-bold text-warning">{{ $lowStockCount }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-box-open fa-2x text-danger me-3"></i>
                    <div>
                        <h6 class="text-muted mb-2">Out of Stock</h6>
                        <h4 class="fw-bold text-danger">{{ $outOfStockCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
    <!-- Inventory Table -->
        <div class="col-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <div>Inventory Items</div>
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <!-- Left side: Search + Filter -->
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <!-- Search Box -->
                            <form method="GET" action="{{ route('inventory.index') }}" class="d-flex align-items-center gap-2">
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Search items..." value="{{ request('search') }}" style="width: 180px;">
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </form>

                            <!-- Category Filter Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fa fa-filter"></i> Category
                                </button>
                                <ul class="dropdown-menu p-2" style="min-width: 200px;">
                                    <form method="GET" action="{{ route('inventory.index') }}">
                                        @foreach($categories as $category)
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="category_id"
                                                    value="{{ $category->id }}" id="cat{{ $category->id }}"
                                                    onchange="this.form.submit()"
                                                    {{ request('category_id') == $category->id ? 'checked' : '' }}>
                                                <label class="form-check-label" for="cat{{ $category->id }}">
                                                    {{ $category->category_name }}
                                                </label>
                                            </div>
                                        </li>
                                        @endforeach
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="category_id" value=""
                                                    id="catAll" onchange="this.form.submit()"
                                                    {{ request('category_id') == '' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="catAll">All Categories</label>
                                            </div>
                                        </li>
                                    </form>
                                </ul>
                            </div>
                        </div>

                        <!-- Right side: PDF button -->
                        <a href="{{ route('inventory.report') }}" class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="fa-solid fa-file-pdf"></i> Export PDF
                        </a>
                    </div>


                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Unit Price (₱)</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->menu->menu_name ?? 'Unnamed' }}</td>
                                <td>{{ $item->category->category_name ?? 'N/A' }}</td>
                                <td>₱{{ number_format($item->cost_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $item->quantity == 0 ? 'danger' : ($item->quantity < 10 ? 'warning text-dark' : 'success') }}">
                                        {{ $item->quantity == 0 ? 'Out of Stock' : ($item->quantity < 10 ? 'Low Stock' : 'In Stock') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <!-- Edit button -->
                                    <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal"
                                        data-bs-target="#editModal" data-id="{{ $item->id }}"
                                        data-name="{{ $item->menu->menu_name ?? 'Unnamed' }}"
                                        data-category="{{ $item->category_id }}" data-quantity="{{ $item->quantity }}"
                                        data-price="{{ $item->cost_price }}" data-unit="{{ $item->unit }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>


                                    <!-- Archive button -->
                                    <button type="button" class="btn btn-sm btn-outline-warning archived" data-bs-toggle="modal"
                                        data-bs-target="#confirmArchiveModal" data-id="{{ $item->id }}"
                                        data-name="{{ $item->menu->menu_name ?? 'Unnamed' }}">
                                        <i class="fa-solid fa-box-archive"></i>
                                    </button>

                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No items found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

               
                </div>
            </div>
            <!-- Pagination -->
            <div class="mt-3">
                {{ $items->links('pagination::bootstrap-5') }}
            </div>
        </div>
           <div class="col-lg-4">

        <!-- Stock Insights -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-chart-pie text-primary me-2"></i> Stock Insights
            </div>
            <div class="card-body">
                <canvas id="stockStatusChart" style="height:200px"></canvas>
            </div>
        </div>

        <!-- Items by Category -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-boxes-stacked text-success me-2"></i> Items by Category
            </div>
            <div class="card-body">
                <canvas id="itemsByCategoryChart" style="height:200px"></canvas>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-gauge-high text-warning me-2"></i> Quick Stats
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fa-solid fa-cubes text-primary me-2"></i> <strong>Total Items:</strong> {{ $totalItems }}</li>
                    <li class="mb-2"><i class="fa-solid fa-triangle-exclamation text-warning me-2"></i> <strong>Low Stock:</strong> {{ $lowStockCount }}</li>
                    <li class="mb-2"><i class="fa-solid fa-ban text-danger me-2"></i> <strong>Out of Stock:</strong> {{ $outOfStockCount }}</li>
                    <li><i class="fa-solid fa-warehouse text-secondary me-2"></i> <strong>Status:</strong> {{ $stockStatus['text'] }}</li>
                </ul>
            </div>
        </div>

    </div>
    </div>
    <!-- Confirm Archive Modal -->
    <div class="modal fade" id="confirmArchiveModal" tabindex="-1" aria-labelledby="confirmArchiveLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="archiveForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-dark">
                        <h5 class="modal-title" id="confirmArchiveLabel">
                            <i class="fa-solid fa-box-archive me-2"></i> Confirm Archive
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                            Are you sure you want to archive
                            <strong id="archiveItemName">this item</strong>?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-warning">Yes, Archive</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">
                            <i class="fa-solid fa-pen me-2"></i> Edit Item
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Item Name</label>
                            <input type="text" class="form-control" id="edit_item_name" name="item_name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit_quantity" name="quantity" min="0"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cost Price (₱)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_cost_price" name="cost_price"
                                min="0" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unit</label>
                            <input type="text" class="form-control" id="edit_unit" name="unit" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Archived Items Modal -->
    <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="archiveModalLabel"> <i class="fa-solid fa-box-archive me-2"></i>
                        Archived Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($archivedItems->isEmpty())
                    <p class="text-muted text-center mb-0">No archived items found.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Unit Price (₱)</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($archivedItems as $archived)
                                <tr>
                                    <td>{{ $archived->menu->menu_name ?? 'Unnamed' }}</td>
                                    <td>{{ $archived->category->category_name ?? 'N/A' }}</td>
                                    <td>₱{{ number_format($archived->cost_price, 2) }}</td>
                                    <td>{{ $archived->quantity }}</td>
                                    <td>{{ $archived->unit }}</td>
                                    <td class="d-flex gap-2 align-items-center justify-content-center">
                                        <form action="{{ route('inventory.restore', $archived->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="fa-solid fa-rotate-left"></i> Restore
                                            </button>

                                        </form>
                                        <form action="{{ route('inventory.destroy', $archived->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to permanently delete this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Restock Modal (Add or Restock Item) -->
    <div class="modal fade" id="restockModal" tabindex="-1" aria-labelledby="restockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form action="{{ route('inventory.store') }}" method="POST" id="inventoryForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="restockModalLabel">Add / Restock Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <!-- LEFT COLUMN: Add or Restock -->
                            <div class="col-md-6 border-end">
                                <h6 class="fw-bold mb-3">Add or Restock Item</h6>

                                <div class="mb-3">
                                    <label class="form-label">Item Name</label>
                                    <input type="text" class="form-control" id="item_name" name="item_name"
                                        placeholder="Enter or select item name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">-- Select Category --</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Current Quantity</label>
                                    <input type="number" class="form-control" id="current_quantity"
                                        name="current_quantity" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Restock Amount</label>
                                    <input type="number" class="form-control" id="restock_amount" name="quantity"
                                        min="0" placeholder="Enter restock quantity">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Unit Price (₱)</label>
                                    <input type="number" step="0.01" class="form-control" id="cost_price"
                                        name="cost_price" min="0" placeholder="Enter unit price">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Unit</label>
                                    <input type="text" class="form-control" id="unit" name="unit" value="pcs">
                                </div>
                            </div>

                            <!-- RIGHT COLUMN: Existing Menu Items -->
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Select Existing Menu Item</h6>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Select</th>
                                                <th>Item Name</th>
                                                <th>Category</th>
                                                <th>Price (₱)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($menus as $menu)
                                            <tr>
                                                <td>
                                                    <input type="radio" name="menu_id" value="{{ $menu->id }}"
                                                        data-name="{{ $menu->menu_name }}"
                                                        data-category="{{ $menu->category_id }}"
                                                        data-price="{{ $menu->price }}"
                                                        data-unit="{{ $menu->unit ?? 'pcs' }}"
                                                        data-quantity="{{ $menu->inventory->quantity ?? 0 }}">
                                                </td>
                                                <td>{{ $menu->menu_name }}</td>
                                                <td>{{ $menu->category->category_name ?? 'N/A' }}</td>
                                                <td>₱{{ number_format($menu->price, 2) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No existing menu items
                                                    found.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <small class="text-muted">
                                    Select a menu item to restock, or fill in the left form to add a new one.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
   <!-- Auto-fill Script -->
    <script>
    document.querySelectorAll('input[name="menu_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('item_name').value = this.dataset.name;
            document.getElementById('category_id').value = this.dataset.category;
            document.getElementById('cost_price').value = this.dataset.price;
            document.getElementById('unit').value = this.dataset.unit;
            document.getElementById('current_quantity').value = this.dataset.quantity;
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            // Extract data attributes
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const category = button.getAttribute('data-category');
            const quantity = button.getAttribute('data-quantity');
            const price = button.getAttribute('data-price');
            const unit = button.getAttribute('data-unit');

            // Fill form inputs
            document.getElementById('edit_item_name').value = name;
            document.getElementById('edit_category_id').value = category;
            document.getElementById('edit_quantity').value = quantity;
            document.getElementById('edit_cost_price').value = price;
            document.getElementById('edit_unit').value = unit;

            // Set dynamic form action
            document.getElementById('editForm').action = `/inventory/${id}/update`;

        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const confirmArchiveModal = document.getElementById('confirmArchiveModal');
        confirmArchiveModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const itemId = button.getAttribute('data-id');
            const itemName = button.getAttribute('data-name');

            // Set item name in modal
            document.getElementById('archiveItemName').textContent = itemName;

            // Update form action dynamically
            const form = document.getElementById('archiveForm');
            form.action = `/inventory/${itemId}/archive`;
        });
    });
    document.addEventListener('DOMContentLoaded', function () {

        const ctxStatus = document.getElementById('stockStatusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['In Stock', 'Low Stock', 'Out of Stock'],
                datasets: [{
                    data: [
                        {{ $totalItems - ($lowStockCount + $outOfStockCount) }},
                        {{ $lowStockCount }},
                        {{ $outOfStockCount }}
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Stock Status Distribution' }
                }
            }
        });

        const ctxCategory = document.getElementById('itemsByCategoryChart').getContext('2d');
        new Chart(ctxCategory, {
            type: 'bar',
            data: {
                labels: {!! json_encode($categories->pluck('category_name')) !!},
                datasets: [{
                    label: 'Items per Category',
                    data: {!! json_encode(
                        $categories->map(fn($cat) => $cat->inventories()->count())
                    ) !!},
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } },
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Items by Category' }
                }
            }
        });
    });
    </script>

@endsection