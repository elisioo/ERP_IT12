@extends('layout.inventory_app')

@section('content')

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5">Order Management</h5>
            <small class="text-muted">Manage your orders efficiently</small>
        </div>

        <div class="d-flex gap-2">
            <!-- View Archived Button (opens modal) -->
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                data-bs-target="#archivedOrdersModal" id="viewArchivedBtn">
                <i class="fa-solid fa-box-archive"></i> View Archived
            </button>

            <!-- Create Order -->
            <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Create Order
            </a>
        </div>
    </div>

    <div class="row">
        <!-- LEFT COLUMN -->
        <div class="col-lg-9">
            <!-- Orders Table -->
            <form method="POST" action="{{ route('orders.bulkDelete') }}" id="bulkDeleteForm">
                @csrf
                @method('DELETE')

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                        <span>Orders List</span>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-filter"></i> Filter
                            </button>

                            <!-- Ellipsis Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <button type="submit" form="bulkDeleteForm" class="dropdown-item">
                                            Archive Selected
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>Order No.</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="{{ $order->id }}"></td>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>
                                        @foreach($order->lines as $line)
                                        {{ $line->menu->menu_name }} (x{{ $line->quantity }})<br>
                                        @endforeach
                                    </td>
                                    <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @if($order->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($order->status === 'processing')
                                        <span class="badge bg-info text-dark">Processing</span>
                                        @elseif($order->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                        @elseif($order->status === 'canceled')
                                        <span class="badge bg-danger">Canceled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}"
                                            class="btn btn-sm btn-outline-dark">View</a>
                                        <a href="{{ route('orders.edit', $order->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- RIGHT COLUMN (Menus Summary) -->
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    Menus
                    <span class="small text-muted">Trending & Latest</span>
                </div>
                <div class="card-body">
                    <!-- Trending -->
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-fire text-danger"></i> Trending</h6>
                    @forelse($trendingMenus as $menu)
                    <div class="mb-3 d-flex align-items-center border-bottom pb-2">
                        <img src="{{ $menu->image ? asset('storage/'.$menu->image) : '/images/default-menu.jpg' }}"
                            class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $menu->menu_name }}</h6>
                            <small class="text-muted">₱{{ number_format($menu->price, 2) }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No trending menus yet.</p>
                    @endforelse

                    <!-- Latest -->
                    <h6 class="fw-bold mt-4 mb-3"><i class="fa-solid fa-bolt text-primary"></i> Latest</h6>
                    @forelse($latestMenus as $menu)
                    <div class="mb-3 d-flex align-items-center border-bottom pb-2">
                        <img src="{{ $menu->image ? asset('storage/'.$menu->image) : '/images/default-menu.jpg' }}"
                            class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $menu->menu_name }}</h6>
                            <small class="text-muted">₱{{ number_format($menu->price, 2) }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No recent menus yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ ARCHIVED ORDERS MODAL -->
<div class="modal fade" id="archivedOrdersModal" tabindex="-1" aria-labelledby="archivedOrdersModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="archivedOrdersModalLabel">
                    <i class="fa-solid fa-box-archive"></i> Archived Orders
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="archivedOrdersTable" class="text-center text-muted">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading archived orders...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkbox Select All -->
<script>
document.getElementById('selectAll').addEventListener('change', function(e) {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = e.target.checked);
});

// Load archived orders via AJAX when modal opens
document.getElementById('viewArchivedBtn').addEventListener('click', function() {
    fetch("{{ route('orders.archived') }}")
        .then(response => response.text())
        .then(html => {
            document.getElementById('archivedOrdersTable').innerHTML = html;
        })
        .catch(() => {
            document.getElementById('archivedOrdersTable').innerHTML =
                "<p class='text-danger'>Failed to load archived orders.</p>";
        });
});
</script>

@endsection